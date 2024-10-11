<?php
include_once __DIR__.'/../models/DAOs/UserDao.php';
include_once __DIR__.'/../models/DAOs/ItemDao.php';

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
                case 'onlyNumbersAndCharacters':
                    if(!empty($formResults[$key]['value']))
                    {
                        $formResults[$key]['value'] = trim($formResults[$key]['value']);
                        $pattern = '/^(([0-9]*)(\p{L}\p{M}*+\-*\h*)*)*$/u';
                        if(!preg_match($pattern, $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." can only contain numbers, letters and spaces";
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
                case 'twoDecimals':
                    if(!empty($formResults[$key]['value']))
                    {
                        if(!preg_match('/^[0-9]*[.,]{1}[0-9]{2}$/', $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." input must be a positive number with two decimals";
                        }
                    }
                    break;
                case 'fullNumber':
                    if(!empty($formResults[$key]['value']))
                    {
                        if(!preg_match('/^[0-9]*$/', $formResults[$key]['value']))
                        {
                            $formResults[$key]['error'] = $key." input must be a full number";
                        }
                    }
                    break;
                case 'min':
                    if(!empty($formResults[$key]['value']))
                    {
                        $minValue = $parts[1];
                        if($formResults[$key]['value'] < $minValue)
                        {
                            $formResults[$key]['error'] = $key." input must be greater than " . $minValue;
                        }
                    }
                    break;
                case 'ValidImage':
                    if(empty($_FILES["ImagesToUpload"]["tmp_name"]))
                    {
                        $formResults[$key]['error'] = " file can not be empty";
                        break;
                    }
                    if($_FILES["ImagesToUpload"]["size"] > 5000000)
                    {
                        $formResults[$key]['error'] = "file size must not exceed 5MB";
                        break;
                    }

                    $target_file = basename($_FILES["ImagesToUpload"]["name"]);
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
                    {
                        $formResults[$key]['error'] = "Only JPG, JPEG, PNG files are allowed";
                        break;
                    }
                    break;
                case 'notMoreThanAvailable':
                    if(!empty($formResults[$key]['value']))
                    {
                        $page = $this->dataExtractor->GetPostVar('page');
                        $pageRequest = explode('/', $page, 2);
                        $itemId = $pageRequest[1];
                        $itemDao = new ItemDao();
                        $item = $itemDao->GetItemById($itemId);
                        if($item != null)
                        {
                            if($item->GetStock() < (int)$formResults[$key]['value'])
                            {
                                $formResults[$key]['error'] = "You can't buy more than available items";
                            }
                        }
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
    }
}