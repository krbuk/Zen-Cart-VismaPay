<?php
$define = [
   'MODULE_PAYMENT_VISMAPAY_TEXT_TITLE' => 'VismaPay - Verkkomaksu',
   'MODULE_PAYMENT_VISMAPAY_TEXT_DESCRIPTION' => 'Visma Pay - ASETUKSET <br>Tilaa Visma Pay oheisella lomakkeella. Tilaukset käsittely kahden arkipäivän kuluessa. Luottokorttimaksujen aktivoiminen edellyttää yrityksen tietoja sekä sitä, että verkkokaupan toimitus-, palautus- ja maksuehdot ovat kunnossa
       <a href="https://www.visma.fi/vismapay" target="_blank">Hanki lisätietoja</a><br><br>
       <a href="https://www.vismapay.com/authenticate" target="_blank">Kirjaudu Visma Pay kauppias portaaliin</a><br><br>
	   Yksityinen rajapinta-avain : API key / Rajapinta-avain<br>
	   Yksityinen salausavain : Private key / Yksityinen salausavain<br>
	   Tilausnumeron etuliite : Tilausnumeron etuliite voi sisältää ainoastaan kirjaimet a-z, numeroita.',
   'MODULE_PAYMENT_PAYMENT_DESCRIPTION' => 'Maksa ostoksesi turvallisesti verkkopankin kautta, korttimaksulla, lompakkopalvelulla tai luottolaskulla.',
   'MODULE_PAYMENT_VISMAPAY_TEXT_API_ERROR' => 'Kauppiaan maksutapoja ei saada. Tarkista, että yksityinen rajapinta-avain ja yksityinen salausavain ovat oikein ',
   'MODULE_PAYMENT_VISMAPAY_CURRENCY' => ' valuutta ',
   'MODULE_PAYMENT_VISMAPAY_EXCEPTION' => ' poikkeus : ',
   'MODULE_PAYMENT_VISMAPAY_ONLYEUR' => '<strong>Salli vain euromääräiset maksut</strong>, eikä tilausvaluutta ollut euroa: ',
   'MODULE_PAYMENT_VISMAPAY_ALERT_TEST' => 'Huomio: Testitila',
   'MODULE_PAYMENT_VISMAPAY_ERROR' => 'Maksu peruutettu / epäonnistui.',
   'MODULE_PAYMENT_VISMAPAY_MAC_ERROR' => 'Visma Pay virhe MAC-laskennassa. ',
   'MODULE_PAYMENT_VISMAPAY_REWARD_POINT_TEXT' => 'Bonuspisteet',
   'MODULE_PAYMENT_VISMAPAY_COUPON_TEXT' => 'Alennuskupongit',
   'MODULE_PAYMENT_VISMAPAY_GIFT_TEXT' => 'Lahjakortit',
   'MODULE_PAYMENT_VISMAPAY_LOWORDER_TEXT' => 'Pientilauslisä',
   'MODULE_PAYMENT_VISMAPAY_FREE_SHPING' => 'Ilmainen toimitus',
   'MODULE_PAYMENT_VISMAPAY_GROUP_TEXT' => 'Ryhmä alennus',
   'MODULE_PAYMENT_VISMAPAY_SUM_ROUND' => 'Summa pyöreä',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_METHOD' => 'Maksutapa : ',
   'MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER' => 'Tilaustunnus ',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_ERROR' => 'Virhe! VismaPay verkkomaksupalvelu ei vastaa.',
   'MODULE_PAYMENT_VISMAPAY_SELECET_OTHER' => 'Kilikka takaisin nappi ja valitse muu maksutapa',	
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_0' => '[Maksu suoritettu] ',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_1' => '[Virheellinen tunniste]',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_2' => '[Maksu epäonnistui] ',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_3' => '[Maksu suoritettu, vaatii hyväksynnän Visma Pay extranetissä!] ',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_4' => '[Maksu ei vielä suoritettu] ',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_10' => '[Huoltokatko ] ',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_AUTHRORIZED' => 'Maksu varmennettu.',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_SETTLED' => 'Maksu veloitettu.',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_BANKS' => 'Pankit',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_CREDITCARDS' => 'Korttimaksut',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_CREDITINVOICE' => 'Luottolaskut',
   'MODULE_PAYMENT_VISMAPAY_PAYMENT_WALLETS' => 'Lompakot',
   'MODULE_PAYMENT_VISMAPAY_TEXT_CONDITIONS_DESCRIPTION' => '<span class="termsdescription">Ole hyvä ja hyväksy tilausehdot ruksittamalla seuraava laatikko. Ehdot voit lukea <a href="' . zen_href_link(FILENAME_SHIPPING) . '"><span class="pseudolink">täältä</span></span></a>.',
   'MODULE_PAYMENT_VISMAPAY_TEXT_CONDITIONS_CONFIRM' => '<span class="termsiagree">Olen lukenut ja hyväksynyt tilausehdot.</span>',
   'MODULE_PAYMENT_VISMAPAY_NOTAVAILABLE' => 'Tilausta varten ei ole käytettävissä Visma Pay maksutapoja : ',
   'MODULE_PAYMENT_VISMAPAY_UNABLE_CREATE_PAYMENT' => 'Maksua ei voi luoda  ',
   'MODULE_PAYMENT_VISMAPAY_TRYAGAIN' => 'Visma Pay -järjestelmä on tällä hetkellä huollossa. Yritä uudelleen muutaman minuutin kuluttua',
   'MODULE_PAYMENT_VISMAPAY_MAINTENANCE' => 'Visma Pay -järjestelmän ylläpito käynnissä.',
   'MODULE_PAYMENT_VISMAPAY_MERCHANT_API' => 'Validointivirhe Visma Pay rajapinnassa. Tarkista salaus- ja rajapinta-avaimet.',
   'MODULE_PAYMENT_VISMAPAY_3D_USED' => '3-D Secure varmennettu tapahtuma.',
   'MODULE_PAYMENT_VISMAPAY_3D_NOT_USED' => '3-D Secure varmennusta ei käytetty.',
   'MODULE_PAYMENT_VISMAPAY_3D_SUPPORTED' => '3-D Secure varmennusta yritettiin, mutta kortinmyöntäjä tai -haltija ei käytä varmennusta.',
   'MODULE_PAYMENT_VISMAPAY_3D_NO_CONNECTION' => '3D-suojaus: Ei yhteyttä hankkijaan.',
   'MODULE_PAYMENT_VISMAPAY_CARD_LOST' => 'Kortti on ilmoitettu kadonneeksi tai varastetuksi.',
   'MODULE_PAYMENT_VISMAPAY_CARD_DECLINE' => 'Yleinen hylkäys. Kortinhaltijan tulisi ottaa yhteyttä kortinmyöntäjään selvittääkseen miksi maksu epäonnistui.',
   'MODULE_PAYMENT_VISMAPAY_CARD_INSUFFICENT_FUND' => 'Riittämättömät varat. Kortinhaltijan tulisi tarkistaa, että kortilla on katetta ja verkkomaksaminen on aktivoitu kortille.',
   'MODULE_PAYMENT_VISMAPAY_CARD_EXPIRED' => 'Vanhentunut kortti',
   'MODULE_PAYMENT_VISMAPAY_CARD_WITHDRAWAL' => 'Kortin maksuraja ylittyi.',
   'MODULE_PAYMENT_VISMAPAY_CARD_RESTRICTED' => 'Rajoitettu kortti. Kortinhaltijan tulisi varmistaa, että verkkomaksaminen on aktivoitu kortille.',
   'MODULE_PAYMENT_VISMAPAY_CARD_TIMOUT' => 'Yhteysongelma korttimaksujen prosessoijaan. Maksua tulisi yrittää uudelleen.',
   'MODULE_PAYMENT_VISMAPAY_CARD_NO_ERROR' => 'Ei koodivirhettä ',
   'MODULE_PAYMENT_VISMAPAY_SELECT' => 'Sinun on valittava vähintään yksi maksutapa.',
   'MODULE_PAYMENT_VISMAPAY_IMMERSION' => '<h2>VALITSE</h2><br>hallintapaneelin kautta Moduulit->Maksu Moduulit->VismaPay<br><strong>Upotus</strong> valitse 0 Pois käytöstä:<br>',	
];
return $define;	
?>