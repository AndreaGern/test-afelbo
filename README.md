# afelboGestionale

## Introduzione

afelboGestionale è un'applicazione web sviluppata con Laravel, progettata per gestire vari aspetti di un'azienda, dalla contabilità alla gestione dei prodotti. Questa piattaforma è ideale per le aziende che necessitano di un sistema integrato per gestire le operazioni quotidiane.

## Funzionalità

### Controllers

- Accountability: Gestisce tutte le operazioni contabili.
- Client: Permette la gestione dei clienti.
- Commission: Gestisce le commissioni.
- Distribution: Occupa della distribuzione dei prodotti.
- Operator: Gestisce gli operatori.
- Order: Gestisce gli ordini.
- Product: Gestisce i prodotti.
- SettingType: Gestisce i tipi di impostazioni.
- StoneClass & StoneType: Gestiscono le informazioni relative alle pietre.

### Models

- Client: Modello per la gestione dei clienti.
- Commission: Modello per la gestione delle commissioni.
- Distribution: Modello per la gestione della distribuzione.
- Operator: Modello per la gestione degli operatori.
- Order: Modello per la gestione degli ordini.
- Product: Modello per la gestione dei prodotti.
- SettingType: Modello per la gestione dei tipi di impostazioni.
- Stone & StoneClass: Modelli per la gestione delle pietre.

## Installazione

- Clona il repository

```bash
git clone
```

## Dump db on server -> da fare in folder backup 
mysqldump -u forge -p afelbogestionale > backup-Dic_16_2023.sql
## Download del nuovo dump in locale
scp forge@188.166.73.72:/home/forge/backup_db/backup-Dic_16_2023.sql .

