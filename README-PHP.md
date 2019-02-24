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

Константы `php_CPCSP`:

```
MEMORY_STORE = 0
LOCAL_MACHINE_STORE = 1
CURRENT_USER_STORE = 2
ACTIVE_DIRECTORY_USER_STORE = 3
SMART_CARD_USER_STORE = 4
STORE_OPEN_READ_ONLY = 0
STORE_OPEN_READ_WRITE = 1
STORE_OPEN_MAXIMUM_ALLOWED = 2
STORE_OPEN_EXISTING_ONLY = 128
STORE_OPEN_INCLUDE_ARCHIVED = 256
CERTIFICATE_FIND_SHA1_HASH = 0
CERTIFICATE_FIND_SUBJECT_NAME = 1
CERTIFICATE_FIND_ISSUER_NAME = 2
CERTIFICATE_FIND_ROOT_NAME = 3
CERTIFICATE_FIND_TEMPLATE_NAME = 4
CERTIFICATE_FIND_EXTENSION = 5
CERTIFICATE_FIND_EXTENDED_PROPERTY = 6
CERTIFICATE_FIND_APPLICATION_POLICY = 7
CERTIFICATE_FIND_CERTIFICATE_POLICY = 8
CERTIFICATE_FIND_TIME_VALID = 9
CERTIFICATE_FIND_TIME_NOT_YET_VALID = 10
CERTIFICATE_FIND_TIME_EXPIRED = 11
CERTIFICATE_FIND_KEY_USAGE = 12
CERT_INFO_SUBJECT_SIMPLE_NAME = 0
CERT_INFO_ISSUER_SIMPLE_NAME = 1
CERT_INFO_SUBJECT_EMAIL_NAME = 2
CERT_INFO_ISSUER_EMAIL_NAME = 3
CERT_INFO_SUBJECT_UPN = 4
CERT_INFO_ISSUER_UPN = 5
CERT_INFO_SUBJECT_DNS_NAME = 6
CERT_INFO_ISSUER_DNS_NAME = 7
DIGITAL_SIGNATURE_KEY_USAGE = 128
NON_REPUDIATION_KEY_USAGE = 64
KEY_ENCIPHERMENT_KEY_USAGE = 32
DATA_ENCIPHERMENT_KEY_USAGE = 16
KEY_AGREEMENT_KEY_USAGE = 8
KEY_CERT_SIGN_KEY_USAGE = 4
OFFLINE_CRL_SIGN_KEY_USAGE = 2
CRL_SIGN_KEY_USAGE = 2
ENCIPHER_ONLY_KEY_USAGE = 1
DECIPHER_ONLY_KEY_USAGE = 32768
ENCODE_BASE64 = 0
ENCODE_BINARY = 1
ENCODE_ANY = 4294967295
CADESCOM_ENCRYPTION_ALGORITHM_RC2 = 0
CADESCOM_ENCRYPTION_ALGORITHM_RC4 = 1
CADESCOM_ENCRYPTION_ALGORITHM_DES = 2
CADESCOM_ENCRYPTION_ALGORITHM_3DES = 3
CADESCOM_ENCRYPTION_ALGORITHM_AES = 4
CADESCOM_ENCRYPTION_ALGORITHM_GOST_28147_89 = 25
ENCRYPTION_KEY_LENGTH_MAXIMUM = 0
ENCRYPTION_KEY_LENGTH_40_BITS = 1
ENCRYPTION_KEY_LENGTH_56_BITS = 2
ENCRYPTION_KEY_LENGTH_128_BITS = 3
ENCRYPTION_KEY_LENGTH_192_BITS = 4
ENCRYPTION_KEY_LENGTH_256_BITS = 5
EKU_OTHER = 0
EKU_SERVER_AUTH = 1
EKU_CLIENT_AUTH = 2
EKU_CODE_SIGNING = 3
EKU_EMAIL_PROTECTION = 4
EKU_SMARTCARD_LOGON = 5
EKU_ENCRYPTING_FILE_SYSTEM = 6
SEX_NOT_KNOWN = 0
SEX_MALE = 1
SEX_FEMALE = 2
SEX_NOT_APPLICABLE = 9
PROV_RSA_FULL = 1
PROV_RSA_SIG = 2
PROV_DSS = 3
PROV_FORTEZZA = 4
PROV_MS_EXCHANGE = 5
PROV_SSL = 6
PROV_RSA_SCHANNEL = 12
PROV_DSS_DH = 13
PROV_EC_ECDSA_SIG = 14
PROV_EC_ECNRA_SIG = 15
PROV_EC_ECDSA_FULL = 16
PROV_EC_ECNRA_FULL = 17
PROV_DH_SCHANNEL = 18
PROV_SPYRUS_LYNKS = 20
PROV_RNG = 21
PROV_INTEL_SEC = 22
PROV_REPLACE_OWF = 23
PROV_RSA_AES = 24
KEY_SPEC_KEYEXCHANGE = 1
KEY_SPEC_SIGNATURE = 2
OID_OTHER = 0
OID_AUTHORITY_KEY_IDENTIFIER_EXTENSION = 1
OID_KEY_ATTRIBUTES_EXTENSION = 2
OID_CERT_POLICIES_95_EXTENSION = 3
OID_KEY_USAGE_RESTRICTION_EXTENSION = 4
OID_LEGACY_POLICY_MAPPINGS_EXTENSION = 5
OID_SUBJECT_ALT_NAME_EXTENSION = 6
OID_ISSUER_ALT_NAME_EXTENSION = 7
OID_BASIC_CONSTRAINTS_EXTENSION = 8
OID_SUBJECT_KEY_IDENTIFIER_EXTENSION = 9
OID_KEY_USAGE_EXTENSION = 10
OID_PRIVATEKEY_USAGE_PERIOD_EXTENSION = 11
OID_SUBJECT_ALT_NAME2_EXTENSION = 12
OID_ISSUER_ALT_NAME2_EXTENSION = 13
OID_BASIC_CONSTRAINTS2_EXTENSION = 14
OID_NAME_CONSTRAINTS_EXTENSION = 15
OID_CRL_DIST_POINTS_EXTENSION = 16
OID_CERT_POLICIES_EXTENSION = 17
OID_POLICY_MAPPINGS_EXTENSION = 18
OID_AUTHORITY_KEY_IDENTIFIER2_EXTENSION = 19
OID_POLICY_CONSTRAINTS_EXTENSION = 20
OID_ENHANCED_KEY_USAGE_EXTENSION = 21
OID_CERTIFICATE_TEMPLATE_EXTENSION = 22
OID_APPLICATION_CERT_POLICIES_EXTENSION = 23
OID_APPLICATION_POLICY_MAPPINGS_EXTENSION = 24
OID_APPLICATION_POLICY_CONSTRAINTS_EXTENSION = 25
OID_AUTHORITY_INFO_ACCESS_EXTENSION     = 26
OID_SERVER_AUTH_eku = 100
OID_CLIENT_AUTH_eku = 101
OID_CODE_SIGNING_eku = 102
OID_EMAIL_PROTECTION_eku = 103
OID_IPSEC_END_SYSTEM_eku = 104
OID_IPSEC_TUNNEL_eku = 105
OID_IPSEC_USER_eku = 106
OID_TIME_STAMPING_eku = 107
OID_CTL_USAGE_SIGNING_eku = 108
OID_TIME_STAMP_SIGNING_eku = 109
OID_SERVER_GATED_CRYPTO_eku = 110
OID_ENCRYPTING_FILE_SYSTEM_eku = 111
OID_EFS_RECOVERY_eku = 112
OID_WHQL_CRYPTO_eku = 113
OID_NT5_CRYPTO_eku = 114
OID_OEM_WHQL_CRYPTO_eku = 115
OID_EMBEDED_NT_CRYPTO_eku = 116
OID_ROOT_LIST_SIGNER_eku = 117
OID_QUALIFIED_SUBORDINATION_eku = 118
OID_KEY_RECOVERY_eku = 119
OID_DIGITAL_RIGHTS_eku = 120
OID_LICENSES_eku = 121
OID_LICENSE_SERVER_eku = 122
OID_SMART_CARD_LOGON_eku = 123
OID_PKIX_POLICY_QUALIFIER_CPS = 124
OID_PKIX_POLICY_QUALIFIER_USERNOTICE = 125
AUTHENTICATED_ATTRIBUTE_SIGNING_TIME = 0
AUTHENTICATED_ATTRIBUTE_DOCUMENT_NAME = 1
AUTHENTICATED_ATTRIBUTE_DOCUMENT_DESCRIPTION = 2
ATTRIBUTE_OTHER = 4294967295
CHECK_NONE = 0
CHECK_TRUSTED_ROOT = 1
CHECK_TIME_VALIDITY = 2
CHECK_SIGNATURE_VALIDITY = 4
CHECK_ONLINE_REVOCATION_STATUS = 8
CHECK_OFFLINE_REVOCATION_STATUS = 16
CHECK_COMPLETE_CHAIN = 32
CHECK_NAME_CONSTRAINTS = 64
CHECK_BASIC_CONSTRAINTS = 128
CHECK_NESTED_VALIDITY_PERIOD = 256
CHECK_ONLINE_ALL = 495
CHECK_OFFLINE_ALL = 503
STRING_TO_UCS2LE = 0
BASE64_TO_BINARY = 1
CERTIFICATE_INCLUDE_CHAIN_EXCEPT_ROOT = 0
CERTIFICATE_INCLUDE_WHOLE_CHAIN = 1
CERTIFICATE_INCLUDE_END_ENTITY_ONLY = 2
CADES_DEFAULT = 0
CADES_BES = 1
CADES_X_LONG_TYPE_1 = 93
CADES_T = 5
VERIFY_SIGNATURE_ONLY = 0
VERIFY_SIGNATURE_AND_CERTIFICATE = 1
HASH_ALGORITHM_SHA1 = 0
HASH_ALGORITHM_MD2 = 1
HASH_ALGORITHM_MD4 = 2
HASH_ALGORITHM_MD5 = 3
HASH_ALGORITHM_SHA_256 = 4
HASH_ALGORITHM_SHA_384 = 5
HASH_ALGORITHM_SHA_512 = 6
HASH_ALGORITHM_GOSTR_3411 = 100
CADESCOM_HASH_ALGORITHM_CP_GOST_3411 = 100
CADESCOM_HASH_ALGORITHM_CP_GOST_3411_2012_256 = 101
CADESCOM_HASH_ALGORITHM_CP_GOST_3411_2012_512 = 102
XML_SIGNATURE_TYPE_ENVELOPED = 0
XML_SIGNATURE_TYPE_ENVELOPING = 1
XML_SIGNATURE_TYPE_TEMPLATE = 2
```

### Через исходные коды

В скачаном с официального сайта архиве `cades_linux_amd64.tar.gz` есть RPM пакет `cprocsp-pki-2.0.0-amd64-phpcades.rpm`, в котором находятся исходные тексты расширения, в том числе `test_extension.php` (скопирован в `devel/test_extension.php`).
