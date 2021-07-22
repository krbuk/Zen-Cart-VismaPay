<?php
define('MODULE_PAYMENT_VISMAPAY_TEXT_TITLE', 'Verkkomaksu');
define('MODULE_PAYMENT_VISMAPAY_TEXT_DESCRIPTION', 'Visma Pay - ASETUKSET <br>Tilaa Visma Pay oheisella lomakkeella. Tilaukset käsittely kahden arkipäivän kuluessa. Luottokorttimaksujen aktivoiminen edellyttää yrityksen tietoja sekä sitä, että verkkokaupan toimitus-, palautus- ja maksuehdot ovat kunnossa
       <a href="https://www.visma.fi/vismapay" target="_blank">Hanki lisätietoja</a><br><br>
       <a href="https://www.vismapay.com/authenticate" target="_blank">Kirjaudu Visma Pay kauppias portaaliin</a><br><br>
	   Yksityinen rajapinta-avain : API key / Rajapinta-avain<br>
	   Yksityinen salausavain : Private key / Yksityinen salausavain<br>
	   Tilausnumeron etuliite : Tilausnumeron etuliite voi sisältää ainoastaan kirjaimet a-z, numeroita, 	   
	   ');
define('MODULE_PAYMENT_PAYMENT_DESCRIPTION','Maksa ostoksesi turvallisesti verkkopankin kautta, korttimaksulla, lompakkopalvelulla tai luottolaskulla.');
define('MODULE_PAYMENT_VISMAPAY_TEXT_API_ERROR', 'Kauppiaan maksutapoja ei saada. Tarkista, että yksityinen rajapinta-avain ja yksityinen salausavain ovat oikein ');
define('MODULE_PAYMENT_VISMAPAY_CURRENCY',' valuutta ');
define('MODULE_PAYMENT_VISMAPAY_EXCEPTION',' poikkeus : ');
define('MODULE_PAYMENT_VISMAPAY_ONLYEUR','<strong>Salli vain euromääräiset maksut</strong>, eikä tilausvaluutta ollut euroa: ');
define('MODULE_PAYMENT_VISMAPAY_ALERT_TEST', 'Huomio: Testitila');
define('MODULE_PAYMENT_VISMAPAY_ERROR', 'Maksu peruutettu / epäonnistui.');
define('MODULE_PAYMENT_VISMAPAY_MAC_ERROR','Visma Pay virhe MAC-laskennassa. ');
define('MODULE_PAYMENT_VISMAPAY_REWARD_POINT_TEXT', 'Bonuspisteet');
define('MODULE_PAYMENT_VISMAPAY_COUPON_TEXT', 'Alennuskupongit');
define('MODULE_PAYMENT_VISMAPAY_GIFT_TEXT', 'Lahjakortit');
define('MODULE_PAYMENT_VISMAPAY_LOWORDER_TEXT', 'Pientilauslisä');
define('MODULE_PAYMENT_VISMAPAY_FREE_SHPING', 'Ilmainen toimitus');
define('MODULE_PAYMENT_VISMAPAY_GROUP_TEXT', 'Ryhmä alennus');
define('MODULE_PAYMENT_VISMAPAY_SUM_ROUND', 'Summa pyöreä');

// Order more information
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_METHOD', 'Maksutapa : ');
define('MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER', 'Tilaustunnus ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_ERROR', 'Virhe! Soita asiakaspalvelun');

// Payment information
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_0', '[Maksu suoritettu] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_1', '[Virheellinen tunniste]');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_2', '[Maksu epäonnistui] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_3', '[Maksu suoritettu, vaatii hyväksynnän Visma Pay extranetissä!] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_4', '[Maksu ei vielä suoritettu] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_10', '[Huoltokatko ] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_AUTHRORIZED', 'Maksu varmennettu.');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_SETTLED', 'Maksu veloitettu.');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_BANKS', 'Pankit');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_CREDITCARDS', 'Korttimaksut');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_CREDITINVOICE', 'Luottolaskut');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_WALLETS', 'Lompakot');
define('MODULE_PAYMENT_VISMAPAY_TEXT_CONDITIONS_DESCRIPTION', '<span class="termsdescription">Ole hyvä ja hyväksy tilausehdot ruksittamalla seuraava laatikko. Ehdot voit lukea <a href="' . zen_href_link(FILENAME_SHIPPING, '', 'SSL') . '"><span class="pseudolink">täältä</span></span></a>.');
define('MODULE_PAYMENT_VISMAPAY_TEXT_CONDITIONS_CONFIRM', '<span class="termsiagree">Olen lukenut ja hyväksynyt tilausehdot.</span>');
define('MODULE_PAYMENT_VISMAPAY_NOTAVAILABLE','Tilausta varten ei ole käytettävissä Visma Pay maksutapoja : ');
define('MODULE_PAYMENT_VISMAPAY_UNABLE_CREATE_PAYMENT','Maksua ei voi luoda  ');
define('MODULE_PAYMENT_VISMAPAY_TRYAGAIN','Visma Pay -järjestelmä on tällä hetkellä huollossa. Yritä uudelleen muutaman minuutin kuluttua');
define('MODULE_PAYMENT_VISMAPAY_MAINTENANCE','Visma Pay -järjestelmän ylläpito käynnissä.');
define('MODULE_PAYMENT_VISMAPAY_MERCHANT_API','Validointivirhe Visma Pay rajapinnassa. Tarkista salaus- ja rajapinta-avaimet.');
define('MODULE_PAYMENT_VISMAPAY_3D_USED','3-D Secure varmennettu tapahtuma.');
define('MODULE_PAYMENT_VISMAPAY_3D_NOT_USED','3-D Secure varmennusta ei käytetty.');
define('MODULE_PAYMENT_VISMAPAY_3D_SUPPORTED', '3-D Secure varmennusta yritettiin, mutta kortinmyöntäjä tai -haltija ei käytä varmennusta.');
define('MODULE_PAYMENT_VISMAPAY_3D_NO_CONNECTION','3D-suojaus: Ei yhteyttä hankkijaan.');
define('MODULE_PAYMENT_VISMAPAY_CARD_LOST','Kortti on ilmoitettu kadonneeksi tai varastetuksi.');
define('MODULE_PAYMENT_VISMAPAY_CARD_DECLINE','Yleinen hylkäys. Kortinhaltijan tulisi ottaa yhteyttä kortinmyöntäjään selvittääkseen miksi maksu epäonnistui.');
define('MODULE_PAYMENT_VISMAPAY_CARD_INSUFFICENT_FUND','Riittämättömät varat. Kortinhaltijan tulisi tarkistaa, että kortilla on katetta ja verkkomaksaminen on aktivoitu kortille.');
define('MODULE_PAYMENT_VISMAPAY_CARD_EXPIRED','Vanhentunut kortti');
define('MODULE_PAYMENT_VISMAPAY_CARD_WITHDRAWAL','Kortin maksuraja ylittyi.');
define('MODULE_PAYMENT_VISMAPAY_CARD_RESTRICTED','Rajoitettu kortti. Kortinhaltijan tulisi varmistaa, että verkkomaksaminen on aktivoitu kortille.');
define('MODULE_PAYMENT_VISMAPAY_CARD_TIMOUT','Yhteysongelma korttimaksujen prosessoijaan. Maksua tulisi yrittää uudelleen.');
define('MODULE_PAYMENT_VISMAPAY_CARD_NO_ERROR','Ei koodivirhettä ');
define('MODULE_PAYMENT_VISMAPAY_SELECT','Sinun on valittava vähintään yksi maksutapa.');
?>