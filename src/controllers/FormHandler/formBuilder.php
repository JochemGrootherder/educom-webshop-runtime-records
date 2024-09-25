<?php

class FormBuilder
{
    public function showForm($formResults, $formDataName, $target, $legend, $buttonText)
    {
        openForm($formDataName, $target, $legend);
        foreach(getFormData($formDataName) as $key => $metaData)
        {
            $formResult = ['value' => $formResults[$key]['value'], 'error' => $formResults[$key]['error']];
            showFormField($key, $metaData, $formResult);
        }
        closeForm($buttonText);
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

}


?>