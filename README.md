# hw1

Davide bucchieri o46002072

Per provarlo: bbc99 con password admin. 
Anche quasi tutti gli altri account che trova vanno con la password admin. Non e' possibile cambiare la password in questi(spunta l'errore che la vecchia password deve essere
piu' lunga di 8 caratteri). Se vuole provare il cambio password le consiglio di creare un nuovo account e farlo su questo.
Per quanto riguarda la gestione degli eventi (intendo la sezione eventi del sito) essi vanno inseriti manualemente nel database.
Il sito, infatti, serve solo al cliente, ma nella mia idea tutti gli interventi seri al database dovrebbero essere fatti in locale con un applicazione e non dal web. Ho sempre 
avuto questa idea in mente, spero vada bene. Ecco il codice per avviare qualche evento: 
call inizia_evento('Bonus 2x', 5, 100);
call inizia_evento('Bonus 2x', 8, 30);
call inizia_evento('Bonus 1_5x', 19, 200);
call inizia_evento('Brutale 1_5x', 1, 40);
call inizia_evento('Brutale 3x', 11, 300);
call inizia_evento('Brutale 5x', 16, 90);
Mi scuso in anticipo se dovesse trovare qualche errore nei testi, non ho avuto il tempo materiale per correggerli.
