<?php
//Вспомогательные функции предварительной инициализации
function SetupStore($location, $name, $mode)
{
    $store = new CPStore();
    $store->Open($location, $name, $mode);
    return $store;
}

function SetupCertificates($location, $name, $mode)
{
    $store = SetupStore($location, $name, $mode);
    $certs = $store->get_Certificates();
    return $certs;

}

function SetupCertificate($location, $name, $mode,
                           $find_type, $query, $valid_only,
                           $number)
{
    $certs = SetupCertificates($location, $name, $mode);
    if($find_type != NULL)
    {
        $certs = $certs->Find($find_type, $query, $valid_only);
        return $certs->Item($number);
    }
    else
    {
        $cert = $certs->Item($number);
        return $cert;
    }
}

function test_CPSignedData_Sign_Verify()
{
    try{
        $content = "test content";
        $address = "http://testca.cryptopro.ru/tsp/tsp.srf";
        $cert = SetupCertificate(CURRENT_USER_STORE, "My", STORE_OPEN_READ_ONLY,
                                 CERTIFICATE_FIND_SUBJECT_NAME, "Test", 0,
                                 1);

        if(!$cert)
            return "Certificate not found";
        $signer = new CPSigner();
        $signer->set_TSAAddress($address);
        $signer->set_Certificate($cert);

        $sd = new CPSignedData();
        $sd->set_ContentEncoding(1);
        $sd->set_Content(base64_encode($content));

        // Второй параметр - тип подписи(1 = CADES_BES):  http://cpdn.cryptopro.ru/default.asp?url=content/cades/namespace_c_ad_e_s_c_o_m_fe49883d8ff77f7edbeeaf0be3d44c0b_1fe49883d8ff77f7edbeeaf0be3d44c0b.html

        //Третий параметр detached - отделенная(true) или совмещенная (false)
        $sm = $sd->SignCades($signer, 1, false, 0);

        printf("Signature is:\n");
        printf($sm);
        printf("\n");

        $sd->VerifyCades($sm, 1, false);
        return 1;
    }catch(Exception $e)
    {
        printf($e->getMessage());
    }
}

if(test_CPSignedData_Sign_Verify() == 1)
{
    printf("TEST OK\n");
}else
{
    printf("TEST FAIL\n");
}

?>
