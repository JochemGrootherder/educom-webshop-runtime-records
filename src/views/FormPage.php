<?php
include __DIR__.'/Page.php';

define ("GENDERS", array('Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'));
define("COMMUNICATION_PREFERENCES", array('Email' => 'Email', 'Phone' => 'Phone', 'Mail' => 'Mail'));
define("ITEM_TYPES", array('CD' => 'CD', 'VINYL' => 'VINYL', 'CASSETTE' => 'CASSETTE'));

define("REGISTERFORMDATA", [
    'Name'  => ['label' => 'Full Name', 'type' => 'text', 'placeholder' => 'Full name', 'validations' => ["notEmpty", "onlyCharacters"]],
    'Email' => ['label' => 'Email', 'type' => 'text', 'placeholder' => 'Example@example.com', 'validations' => ["notEmpty", "validEmail", "uniqueEmail", "toLowerCase"]],
    'Password' => ['label' => 'Password', 'type' => 'password', 'placeholder' => 'Password', 'validations' => ["notEmpty", "minLength:8", "containsUppercase", "containsLowercase", "containsNumber", "containsSpecialChar"]],
    'ConfirmPassword' => ['label' => 'Confirm Password', 'type' => 'password', 'placeholder' => 'Confirm Password', 'validations' => ["matchesPassword"]],
    'DateOfBirth' => ['label' => 'Date Of Birth', 'type' =>'date', 'placeholder' => '', 'validations'=> ['']],
    'Gender' => ['label' => 'Gender', 'type' =>'select', 'options' => GENDERS, 'validations'=> ['notEmpty', 'validOption']]
]);

define ("LOGINFORMDATA", [
    'Email' => ['label' => 'Email', 'type' => 'text', 'placeholder' => 'Example@example.com', 'validations' => ["notEmpty", "emailExists", "loginValid", "toLowerCase"]],
    'Password' => ['label' => 'Password', 'type' => 'password', 'placeholder' => 'Password', 'validations' => []]
]);

define("ADDITEMFORMDATA", [
    'Title'  => ['label' => 'Title', 'type' => 'text', 'placeholder' => 'Title', 'validations' => ["notEmpty", "onlyNumbersAndCharacters"]],
    'Description'  => ['label' => 'Description', 'type' => 'text', 'placeholder' => 'Description', 'validations' => ["notEmpty", "onlyNumbersAndCharacters"]],
    'Artists'  => ['label' => 'Artists', 'type' => 'text', 'placeholder' => 'Artist', 'validations' => ["notEmpty", "onlyNumbersAndCharacters"]],
    //'AddArtist' => ['label' => '', 'type' => 'button', 'value' => 'Add artist', 'onclick' => 'AddArtist', 'validations'=> []],
    'Genres'  => ['label' => 'Genres', 'type' => 'text', 'placeholder' => 'Genre', 'validations' => ["notEmpty", "onlyCharacters"]],
    //'AddGenre' => ['label' => '', 'type' => 'button', 'value' => 'Add genre', 'onclick' => 'AddGenre', 'validations'=> []],
    'Price'  => ['label' => 'Price', 'type' => 'number', 'placeholder' => '0.0', 'step'=> '0.01', 'validations' => ["notEmpty", "twoDecimals", "min:0"]],
    'Year' => ['label' => 'Year', 'type' => 'number', 'placeholder' => '0000', 'step'=> '1', 'validations' => ["notEmpty", "fullNumber", "min:0"]],
    'Type' => ['label' => 'Type', 'type' =>'select', 'options' => ITEM_TYPES, 'validations'=> ['notEmpty', 'validOption']],
    'Stock' => ['label' => 'Stock', 'type' => 'number', 'placeholder' => '0', 'step'=> '1', 'validations' => ["notEmpty", "fullNumber", "min:0"]],
    'ImagesToUpload' => ['label' => 'Images', 'name' => 'ImagesToUpload', 'type' => 'file', 'value' => '','placeholder' => '', 'accept' => '.jpg, .png, .jpeg','validations' => ["ValidImage"]]
]);

define("ADDTOCARTFORMDATA", [
    'Amount' => ['label' => 'Amount', 'type' => 'number', 'placeholder' => '0', 'step'=> '1', 'validations' => ["notEmpty", "fullNumber", "min:1", "notMoreThanAvailable"]]
]);

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
            case 'number':
                echo '
                    <input type="'.$metaData['type'].'"class="form-control" 
                    name="'.$key.'" 
                    placeholder= "'.$metaData['placeholder'].'" 
                    value="'.$formResult['value'].'"
                    step='. $metaData['step'].'>
                    </input>';
                break;
            case 'button':
                echo '<script type="text/javascript" src="'.__DIR__.'/FormScript.php"></script> ';
                echo '
                <button class="add-button" action="index.php?" name="pressedButton" value="'.$metaData['onclick'].'">'. $metaData['value'].'
                </button>'
                ;
                break;
            case 'file':
                echo '
                    <input type="'.$metaData['type'].'"class="form-control" 
                    name="'.$key.'" 
                    id="'.$key.'"
                    placeholder= "'.$metaData['placeholder'].'" 
                    accept="'.$metaData['accept'].'" >
                    </input>';
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

        echo"";
    }

    public function openForm($formDataName, $target, $legend)
    {
        echo '
        <form method="POST" enctype="multipart/form-data">
            <fieldset>
                <input type="hidden" name="page" value="'.$target.'">
                <input type="hidden" name="formDataName" value="'.$formDataName.'">';
        if(!empty($legend))
        {
                echo '<legend>'.$legend.'</legend>';
        }
    }
    
    public function closeForm($buttonText)
    {
        echo'
                <div class="form-group">
                    <label class="control-label" for="send"></label>
                    <button name="pressedButton" value="postForm" action="index.php?" class="btn btn-primary">'.$buttonText.'</button>
                </div>
            </fieldset>
        </form>';
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