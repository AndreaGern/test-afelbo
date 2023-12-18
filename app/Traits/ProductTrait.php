<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\SettingType;
use App\Models\Stone;
use App\Models\StoneClass;
use App\Models\StoneType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait ProductTrait
{
    public function storeProduct(Request $request, $archive = false, $product = null)
    {
        //?* Se arriva il prodotto vuol dire che é stato selezionato da db e quindi lo devo prima aggiornare
        if ($product) {
            //? Clono il prodotto
            $cloneProduct = $product->replicate();
            $cloneProduct->save();
            $cloneProduct->update($request->except(['stones', 'product_quantity_order', 'commission', 'code']));
            $product = $cloneProduct;

            // Questa parte non serve più perché il prodotto viene clonato e quindi non ha più lavorazioni

            //? Per ogni pietra elimino la lavorazione e la pietra stessa che non sará più collegata a nulla
            // $stones = $product->stones()->get();
            // foreach ($stones as $stone) {
            //     $stone->products()->detach();
            //     $stone->delete();
            // }
        } else {
            //? Creo il prodotto
            $product = Product::create($request->except(['stones', 'product_quantity_order', 'commission']));
        }

        $product->update(['archive' => $archive]);
        //? Creo le pietre
        //? Collego le pietre alla classe, tipo pietra, tipo incastonatura
        //? collego prodotto alle pietre tramite le lavorazioni
        $productCode = [];
        foreach ($request->stones as $requestStone) {
            $newStone = $product->stones()->create(Arr::only($requestStone, ['stone_weight', 'client_cost', 'prezzariouser']), ['quantity' => $requestStone['quantity']]);
            $newStone->stoneClass()->associate(StoneClass::find($requestStone['stone_class_id']));
            $newStone->stoneType()->associate(StoneType::find($requestStone['stone_type_id']));
            $newStone->settingType()->associate(SettingType::find($requestStone['setting_type_id']));
            $newStone->save();
            array_push($productCode, $newStone->settingType->code . $requestStone['quantity'] . $newStone->stoneType->code . $newStone->stoneClass->code);
        }

        //? Formula creazione codice
        //* Codice tipo di prodotto/
        //* Tipo incastonatura
        //* Numero di pietre
        //*CL
        //* Classe di pietra
        //* OR/PL10CL3-RR2CL8
        $productCode = "$product->type/" . collect($productCode)->join('-');
        $product->update(['code' => strtoupper($productCode)]);
        return $product;
    }

    public function updateProduct(Request $request, $archive = false, $product = null)
    {
        //?* Se arriva il prodotto vuol dire che é da modificare
        $product->update($request->except(['stones', 'product_quantity_order', 'commission']));

        // Dovrò modifcare il codice del prodotto in base alle pietre che resteranno alla fine nel prodotto. 
        $productCode = [];
        //? Questa é la formula di creazione del codice che bisognerà seguire per modificare il codice del prodotto
        //* Codice tipo di prodotto/
        //* Tipo incastonatura
        //* Numero di pietre
        //* CL
        //* Classe di pietra 
        //* esempio di codice: OR/PL10CL3-RR2CL8


        // Se non c'é proprio il campo stones vuol dire che l'ordine é già stato ripartito e quindi posso modificare solo la quantità, quindi le pietre le lascio stare 
        if (!$request->has('stones')) {
            // Il campo 'stones' non è presente nella richiesta
            return $product;
        }

        //? Se nella request non ci sono pietre vuol dire che devo eliminare tutte le pietre

        if (empty($request->stones)) {
            //? Per ogni pietra elimino la lavorazione e la pietra stessa che non sará più collegata a nulla
            $stones = $product->stones()->get();
            foreach ($stones as $stone) {
                $stone->products()->detach();
                $stone->delete();
            }

            $product->update(['code' => "$product->type/"]);
            return $product;
        }

        /*  Se ci sono le pietre allora devo prendere tutti i processi legati al prodotto. 
                I processi sono la tabella pivot tra prodotto e pietra.
                Quindi ad ogni processo corrisponde una pietra.
                Devo ciclare le pietre della request e per ogni pietra modificare un processo andando in ordine fin quando non finiscono le pietre della request. Non devo confrontare gli id.
            */
        $requestStones = $request->stones;
        if (count($requestStones) > 0) {
            $processes = $product->processes()->get();
            $i = 0;
            foreach ($requestStones as $requestStone) {
                /* Se le pietre della request hanno superato i processi vuol dire che sono nuove pietre e quindi devo crearle */
                if ($i >= count($processes)) {
                    $newStone = $product->stones()->create(Arr::only($requestStone, ['stone_weight', 'client_cost', 'prezzariouser']), ['quantity' => $requestStone['quantity']]);
                    $newStone->stoneClass()->associate(StoneClass::find($requestStone['stone_class_id']));
                    $newStone->stoneType()->associate(StoneType::find($requestStone['stone_type_id']));
                    $newStone->settingType()->associate(SettingType::find($requestStone['setting_type_id']));
                    $newStone->save();
                    array_push($productCode, $newStone->settingType->code . $requestStone['quantity'] . $newStone->stoneType->code . $newStone->stoneClass->code);
                    continue;
                }
                /* Se i processi hanno superato le pietre della request vuol dire che devo eliminare i processi e le pietre in eccesso  */
                if ($i >= count($requestStones)) {
                    // prima di eliminare la pietra la deassocio dalle 3 tabelle delle pietre: stone_class, stone_type, setting_type
                    $processes[$i]->stone->stoneClass()->dissociate();
                    $processes[$i]->stone->stoneType()->dissociate();
                    $processes[$i]->stone->settingType()->dissociate();
                    $processes[$i]->stone->save();
                    // elimino la pietra
                    $processes[$i]->stone()->delete();
                    // elimino il processo
                    $processes[$i]->delete();
                    continue;
                }

                // Se non sono ne in eccesso ne in mancanza allora devo modificare la pietra legata al processo
                $process = $processes[$i];
                $process->stone->update(Arr::only($requestStone, ['stone_weight', 'client_cost', 'prezzariouser']), ['quantity' => $requestStone['quantity']]);
                // prima di associare devo desassociare
                $process->stone->stoneClass()->dissociate();
                $process->stone->stoneClass()->associate(StoneClass::find($requestStone['stone_class_id']));

                $process->stone->stoneType()->dissociate();
                $process->stone->stoneType()->associate(StoneType::find($requestStone['stone_type_id']));

                $process->stone->settingType()->dissociate();
                $process->stone->settingType()->associate(SettingType::find($requestStone['setting_type_id']));

                $process->stone->save();
                array_push($productCode, $process->stone->settingType->code . $requestStone['quantity'] . $process->stone->stoneType->code . $process->stone->stoneClass->code);
                $i++;
            }
        }

        $productCode = "$product->type/" . collect($productCode)->join('-');
        $product->update(['code' => $productCode]);

        return $product;
    }
}
