<?php
include_once __DIR__.'/../models/DAOs/UserDao.php';

class InputValidator
{
    private $dataExtractor;
    function __construct($dataExtractor)
    {
        $this->dataExtractor = $dataExtractor;
    }
    
    private function validateField($key, $metaData, &$formResults)
    {
        foreach($metaData['validations'] as $validation)
        {
            $parts = explode(':', $validation, 3);
            switch ($parts[0]){
                case 'notEmpty':
                    if(empty($formResults[$key]['value']))
                    {
                        $formResults[$key]['error'] = $key .' can not be empty';
                    }
                    break;
                case 'validOption':
                    switch ($key)
                    {
                        case 'communicationPreference':
                            if(!in_array($formResults[$key]['value'], COMMUNICATION_PREFERENCES))
                            {
                                $error_string = "";
                                foreach(COMMUNICATION_PREFERENCES as $communication_method)
                                {
                                    $error_string.= '"'.$communication_method.'" ';
                                }
                                $formResults[$key]['error'] = $key .' must be either:'.$error_string;
                            }
                            break;

                        case 'gender':
                            if(!in_array($formResults[$key]['value'], GENDERS))
                            {
                                $error_string = "";
                                foreach(GENDERS as $GENDER)
                                {
                                    $error_string.= '"'.$GENDER.'" ';
                                }
                                $formResults[$key]['error'] = $key .' must be either:'.$error_string;

                            }
                            break;
                    }
                    break;
                case 'onlyCharacters':
                    if(!empty($formResults[$key]['value']))
                    {
                        $formResults[$key]['value'] = trim($formResults[$key]['value']);
                        $pattern = '/^((\p{L}\p{M}*+\-*\h*)+)$/u';
                        if(!preg_match($pattern, $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." can only contain letters and spaces";
                        }
                    }
                    break;
                case 'notEmptyIf':
                    if(empty($formResults[$key]['value']) && $formResults[$parts[1]]['value'] == $parts[2])
                    {
                        $formResults[$key]['error'] = $key." can not be empty";
                    }
                    break;
                case 'validEmail':
                    if(!empty($formResults[$key]['value']))
                    {
                        if(!filter_var($formResults[$key]['value'], FILTER_VALIDATE_EMAIL))
                        {
                            $formResults[$key]['error'] = "Invalid Email format. Expected: example@example.com";
                        } 
                    } 
                    break;
                case 'validPhoneNumber':
                    if(!empty($formResults[$key]['value']))
                    {
                        //0612345678 
                        //OR 
                        //+31612345678 
                        $pattern = match(strlen($formResults[$key]['value'])) {
                            10 => '/06[0-9]{8}/', 
                            12 => '/[+]316[0-9]{8}/',
                            default => '' 
                        }; 
                    
                        if (empty($pattern) || !preg_match($pattern, $formResults[$key]['value'])) 
                        {
                            $formResults[$key]['error'] = "Phonenumber has an invalid format. Expected format: '0612345678' or '+31612345678'"; 
                        } 
                    }  
                    break;
                case 'validZipcode':
                    
                if(!empty($formResults[$key]['value']))
                {
                    $value = strtoupper($formResults[$key]['value']);
                    $pattern = '/^[0-9]{4}[A-Z]{2}$/';
                    if(!preg_match($pattern, $value))
                    {
                        $formResults[$key]['error'] = "Zipcode has an invalid format. Expected format: '1234AB'";
                    }
                }
                    break;
                case 'uniqueEmail':
                    if(!empty($formResults[$key]['value']))
                    {
                        $userDao = new UserDao();
                        $email = $formResults[$key]['value'];
                        $result = $userDao->GetUserByEmail($email);
                        if($result != null)
                        {
                            $formResults[$key]['error'] = "This email already exists";
                        }
                    }
                    break;
                case 'minLength':
                    $minLength = $parts[1];
                    if(strlen($formResults[$key]['value']) < $minLength)
                    {
                        $formResults[$key]['error'] = $key." must have at least ".$minLength." characters";
                    }
                    break;
                case 'containsUppercase':
                    if(!empty($formResults[$key]['value']))
                    {
                        if(!preg_match('/[A-Z]/', $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." must contain at least one uppercase letter";
                        }
                    }
                    break;
                case 'containsLowercase':
                    if(!empty($formResults[$key]['value']))
                    {
                        if(!preg_match('/[a-z]/', $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." must contain at least one lowercase letter";
                        }
                    }
                    break;
                case 'containsNumber':
                    if(!empty($formResults[$key]['value']))
                    {
                        if(!preg_match('/[0-9]/', $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." must contain at least one number";
                        }
                    }
                    break;
                case 'containsSpecialChar':
                    
                    if(!empty($formResults[$key]['value']))
                    {
                        if(!preg_match('/[^A-Za-z0-9]/', $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." must contain at least one special character";
                        }
                    }
                    break;
                case 'matchesPassword':
                    if(!($formResults[$key]['value'] === $formResults['Password']['value']))
                    {
                        $formResults[$key]['error'] = "Passwords do not match";
                    }
                    break;
                case 'emailExists':
                    if(!empty($formResults[$key]['value']))
                    {
                        $userDao = new UserDao();
                        $email = $formResults[$key]['value'];
                        $result = $userDao->GetUserByEmail($email);
                        if($result == null)
                        {
                            $formResults[$key]['error'] = "This user does not exist";
                        }
                    }
                    break;
                case 'toLowerCase':
                    if(!empty($formResults[$key]['value']))
                    {
                        $formResults[$key]['value'] = strtolower($formResults[$key]['value']);
                    }
                    break;
                case 'loginValid':
                    if(!$this->validateLogin($formResults['Email']['value'], $formResults['Password']['value']))
                    {
                        $formResults[$key]['error'] = "Combination of email and password is incorrect";
                    }
                    break;
                }
        }   
        return $formResults;
    }

    public function validateInput($formData)
    {
        $formResults = $this->dataExtractor->getDataFromPost($formData);
        foreach($formData as $key => $metaData)
        {
            $formResult = $this->validateField($key, $metaData, $formResults);
        }
        return $formResults; 
    }

        
    function validateLogin($email, $password)
    {
        $userDao = new UserDao();
        $user = $userDao->GetUserByEmail($email);
        if($user != null)
        {
            return $user->GetPassword() == $password;
        }
        /*$user = getUserFromFile($email);
        if($user != null)
        {
            if($user['Password'] == $password)
            {
                return true;
            }
        }*/
    }
}