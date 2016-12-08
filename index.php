<?php
/**
 *  titolo: Cumbido_bot
 *  autore: Matteo Enna
 *  licenza GPL3
 **/

define('BOT_TOKEN', '');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

//return true;

// read incoming info and grab the chatID
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["chat"]["id"];

$benvenuto_ray = array(
        'Benvenuto su "Sardegna, il prontuario da bar".',
        'Bot telegram (Open Source) che elenca le buone pratiche da attuare quando al bar 2 o più persone vogliono pagare.',
        'Questo bot telegram è stato realizzato da @matteoenna.',
        'Le regole presenti sono state scritte da Francesco Manca, proprietario del bar "Bosco Seleni" (Lanusei)',
        'Il prontuario è stato pubblicato su Liberos in data 06/09/2012 con le Creative Commons BY-NC-SA',
        '',
        'Se avete aperto questo bot, probabilmente siete alla cassa e due o più persone si sentono in dovere di pagare.',
        'Le regole hanno come esempio Lanusei ma si possono estendere a qualsiasi paese/città.',
        'Per la prima regola digitate /regola1'
    );
		
$benvenuto=implode ("\n",$benvenuto_ray);

$array_rules = array(
        "1" => "In caso di gruppi ove siano anche individui non lanuseini, a pagare il conto sarà l’unico individuo nativo di Lanusei.",
        "2" => "Nel caso in cui nel gruppo vi siano più elementi di Lanusei, a pagare il conto sarà l’individuo più anziano.",
        "3" => "Nel caso in cui sia presente un individuo più giovane ma con più lunga residenza a Lanusei di quello più anziano, sarà quello più giovane a pagare il conto (se dovessero sorgere discussioni su chi è da più tempo residente nel paese, si dovrà esibire un certificato di residenza la cui data non sia anteriore a tre mesi).",
        "4" => "Nel caso in cui nel gruppo non siano presenti individui nativi o residenti a Lanusei, a pagare il conto sarà l’individuo del paese più vicino geograficamente.",
        "5" => "Naturalmente il titolare del diritto di pagare può rinunciarvi per una questione di cortesia a favore di un altro individuo.",
        "6" => "Quanto sopra esposto è comunque soggetto allo “ius rotationis”, ovvero al diritto di tutti i componenti del gruppo a pagare un giro.",
        "7" => "Il personale della caffetteria ha diritto di prelazione sul pagamento del giro: se il barista dice “Questo giro lo offriamo noi” il cliente si deve adeguare. Ma questo capita molto raramente.",
        "8" => "Motivazioni quali “Pago io perché devo cambiare i soldi” o “Prendili da me perché li ho tirati fuori prima io” non hanno valore giuridico e il barista può fare un’eccezione solo in casi particolari: per esempio, nel caso in cui non abbia resto sufficiente e uno dei clienti, pur non avendo titolo a pagare, sia in possesso di cambio.",
        "9" => "Il presente regolamento si applica a qualsiasi prodotto venduto al banco, dai superalcolici ai lecca-lecca.",
        "10"=> "Per evitare problemi con il Ministro delle Pari Opportunità, non si fanno distinzioni tra individui di sesso maschile e femminile.",                 
    );
		
if($update["message"]["text"]=="/start" || $update["message"]["text"]=="/help"){
	$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode($benvenuto);
	file_get_contents($sendto);
	
	die();
    
} else {
    
    $pezzo = explode("regola", $update["message"]["text"]);
    $id = $pezzo[1];
    
    if (is_numeric($id)) {
        if($id <= 10 ){
            $regola_sucessiva = "Se non siete riusciti a risolvere il conflitto potete procedete con la regola sucessiva: /regola".($id+1);
            $inizio = "Portami all'inizio: /start";
            $messaggio = $array_rules[$id]."\n\n".$regola_sucessiva."\n\n".$inizio;

            $sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode($messaggio);
            file_get_contents($sendto);
            
        } else {
            $sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode("Il conflitto dovrebbe essere risolto, è il caso di fare un secondo giro?");
            file_get_contents($sendto);
            die();
        }
    } else {
        $sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode("Comando non trovato, a che giro siete arrivati? Digitate /start");
        file_get_contents($sendto);
        die();
        
    }
}

?>
