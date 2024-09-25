<?php
include 'Page.php';

define ("GENDERS", array('Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Other' => 'Other'));
define("COMMUNICATION_PREFERENCES", array('Email' => 'Email', 'Phone' => 'Phone', 'Mail' => 'Mail'));


abstract class FormPage extends Page
{
    abstract public function ShowTitle();
    abstract public function ShowBody();

    public function showForm($formData, $formResults, $formDataName, $target, $legend, $buttonText)
    {
        $this->openForm($formDataName, $target, $legend);
        foreach($formData as $key => $metaData)
        {
            $formResult = ['value' => $formResults[$key]['value'], 'error' => $formResults[$key]['error']];
            $this->showFormField($key, $metaData, $formResult);
        }
        $this->closeForm($buttonText);
    }
    public function showFormField($key, $metaData, $formResult)
    {
        echo '
        <div class="form-group">
        <label class="control-label">'.$metaData['label'].'</label>';

        switch($metaData['type'])
        {
            case 'textarea':
                echo '
                <textarea class="form-control" name="'.$key.'" placeholder= "'.$metaData['placeholder'].'" ">'.$formResult['value'].'</textarea>';
            break;
            case'select':
                echo '
                <select name="'.$key.'">';
                foreach($metaData['options'] as $option_key => $option_value)
                {
                    echo '<option value="'.$option_key.'"';
                    if($formResult['value'] == $option_key)
                    {
                        echo'selected';
                    }
                    echo' >'.$option_value.'</option>';
                }
                
                echo '</select>';
            break;
            case 'radio':
                foreach($metaData['options'] as $option_key => $option_value)
                {
                    echo '
                    <div class="radio">
                    <input type="radio" name= "'.$key.'" value="'.$option_key.'"';
                    if($formResult['value'] == $option_key)
                    {
                        echo'checked="checked"';
                    }
                    echo '>'.$option_value.'</input>
                    </div>';
                }
            break;
            default:
            echo '
            <input type="'.$metaData['type'].'"class="form-control" name="'.$key.'" placeholder= "'.$metaData['placeholder'].'" value="'.$formResult['value'].'"></input>';
            break;
        }
        
        if(!empty($formResult['error']))
        {
            echo '<span class="error">* '.$formResult['error'].'</span>';
        }
        echo '</div>';

    }

    public function openForm($formDataName, $target, $legend)
    {
        echo '
        <form method="POST" action="index.php?">
            <fieldset>
                <input type="hidden" name="page" value="'.$target.'">
                <input type="hidden" name="formDataName" value="'.$formDataName.'">
                <legend>'.$legend.'</legend>';
    }
    
    public function closeForm($buttonText)
    {
        echo'
                <div class="form-group">
                    <label class="control-label" for="send"></label>
                    <button name="send" class="btn btn-primary">'.$buttonText.'</button>
                </div>
            </fieldset>
        </form>';
    }

    public function validateField($key, $metaData, &$formResults)
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
                            if(userExists($formResults[$key]['value']))
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
                            if(!userExists($formResults[$key]['value']))
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
                        if(!validateLogin($formResults['Email']['value'], $formResults['Password']['value']))
                        {
                            $formResults[$key]['error'] = "Combination of email and password is incorrect";
                        }
                        break;
                    }
            }   
            return $formResults;
        }

        public function containsErrors($formResults)
        {
            $containsErrors = false;
            foreach($formResults as $key => $formResult)
            {
                if(!empty($formResult['error']))
                {
                    return true;
                }
            }
            return $containsErrors;
        }

        public function validateInput($formDataName)
        {
            $formResults = getDataFromPost(getFormData($formDataName));
            return !containsErrors($formResults);
        }
        
    function createEmptyFormResults($formData)
    {
        $formResults;
        //fill an empty form so formBuilder has access to array offset
        foreach($formData as $key => $metaData)
        {
            $formResults[$key] = ['value' => '', 'error' =>''];
        }
        return $formResults;
    }
}