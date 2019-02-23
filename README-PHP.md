# PHP КриптоПро

Документация по использованию PHP КриптоПро скудна.

Попытки достать параметры методов через [Reflection API](http://php.net/manual/ru/book.reflection.php) не приводят к успеху:

```PHP
// Работает
$r = new \ReflectionMethod('DateTime', '__construct');
var_dump($r->getParameters());

// Возвращает пустой массив
$r = new \ReflectionMethod('CPSignedData', 'get_Content');
var_export($r->getParameters());
```

## Список классов и методов

### Через Reflection API

С помощью `devel/reflection.php` получен такой список классов и методов:

```
CPStore
  Open()
  Close()
  get_Certificates()
  get_Location()
  get_Name()

CPCertificates
  Find()
  Item()
  Count()

CPCertificate
  GetInfo()
  FindPrivateKey()
  HasPrivateKey()
  IsValid()
  ExtendedKeyUsage()
  KeyUsage()
  Export()
  Import()
  get_SerialNumber()
  get_Thumbprint()
  get_SubjectName()
  get_IssuerName()
  get_Version()
  get_ValidToDate()
  get_ValidFromDate()
  BasicConstraints()
  PublicKey()
  PrivateKey()

CPKeyUsage
  get_IsPresent()
  get_IsCritical()
  get_IsDigitalSignatureEnabled()
  get_IsNonRepudiationEnabled()
  get_IsKeyEnciphermentEnabled()
  get_IsDataEnciphermentEnabled()
  get_IsKeyAgreementEnabled()
  get_IsKeyCertSignEnabled()
  get_IsCRLSignEnabled()
  get_IsEncipherOnlyEnabled()
  get_IsDecipherOnlyEnabled()

CPExtendedKeyUsage
  get_IsPresent()
  get_IsCritical()
  get_EKUs()

CPEKU
  get_Name()
  set_Name()
  get_OID()
  set_OID()

CPAlgorithm
  get_Name()
  set_Name()
  get_KeyLength()
  set_KeyLength()

CPPrivateKey
  get_ContainerName()
  get_UniqueContainerName()
  get_ProviderName()
  get_ProviderType()
  get_KeySpec()

CPEncodedData
  Format()
  get_Value()

CPPublicKey
  get_Algorithm()
  get_Length()
  get_EncodedKey()
  get_EncodedParameters()

CPOID
  get_Value()
  set_Value()
  get_FriendlyName()

CPAttribute
  set_OID()
  get_OID()
  set_Value()
  get_Value()
  set_Name()
  get_Name()
  set_ValueEncoding()
  get_ValueEncoding()

CPBasicConstraints
  set_IsPresent()
  get_IsPresent()
  set_IsCritical()
  get_IsCritical()
  get_IsCertificateAuthority()
  get_IsPathLenConstraintPresent()
  get_PathLenConstraint()

CPCertificateStatus
  get_Result()
  get_CheckFlag()
  set_CheckFlag()
  EKU()
  get_VerificationTime()
  set_VerificationTime()
  get_UrlRetrievalTimeout()
  set_UrlRetrievalTimeout()
  CertificatePolicies()
  ApplicationPolicies()
  get_ValidationCertificates()

CPEnvelopedData
  get_Content()
  set_Content()
  get_ContentEncoding()
  set_ContentEncoding()
  Encrypt()
  Decrypt()
  get_Recipients()

CPSigner
  get_Certificate()
  set_Certificate()
  get_Options()
  set_Options()
  get_AuthenticatedAttributes()
  get_UnauthenticatedAttributes()
  get_TSAAddress()
  set_TSAAddress()
  get_CRLs()
  get_OCSPResponses()
  get_SigningTime()
  get_SignatureTimeStampTime()
  set_KeyPin()

CPEKUs
  Add()
  get_Count()
  get_Item()
  Clear()
  Remove()

CPAttributes
  Add()
  get_Count()
  get_Item()
  Clear()
  Remove()
  Assign()

CPSigners
  get_Count()
  get_Item()

CPRecipients
  Add()
  get_Count()
  get_Item()
  Clear()

CPSignedData
  SignCades()
  SignHash()
  Sign()
  CoSign()
  CoSignCades()
  CoSignHash()
  EnhanceCades()
  VerifyCades()
  VerifyHash()
  Verify()
  set_ContentEncoding()
  get_ContentEncoding()
  set_Content()
  get_Content()
  get_Signers()
  get_Certificates()

CPHashedData
  Hash()
  SetHashValue()
  get_Value()
  set_Algorithm()
  get_Algorithm()
  set_DataEncoding()
  get_DataEncoding()

CPRawSignature
  VerifyHash()
  SignHash()

CPSignedXML
  set_Content()
  get_Content()
  set_SignatureType()
  set_DigestMethod()
  set_SignatureMethod()
  get_Signers()
  Sign()
  Verify()
```

### Через исходные коды

В скачаном с официального сайта архиве `cades_linux_amd64.tar.gz` есть RPM пакет `cprocsp-pki-2.0.0-amd64-phpcades.rpm`, в котором находятся исходные тексты расширения, в том числе `test_extension.php` (скопирован в `devel/test_extension.php`).
