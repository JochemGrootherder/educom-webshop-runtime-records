<?php
class ProfilePage extends FormPage
{    
    private $formResults = [];
    private $formData = [];
    public function __construct()
    {
        $this->formData = $this->CreateFormData();
        $this->formResults = $this->CreateEmptyFormResults($this->formData);
    }

    
    public static function WithResults($formResults)
    {
        $instance = new self();
        $instance->formResults = $formResults;
        return $instance;
    }
    public function showTitle()
    {
        echo "Profile";

    }
    public function showBody()
    {
        $this->CreateFormResultsForSearchCriteria();
        $this->showForm($this->formData, $this->formResults, 'Search_criteria', 'ProfilePage', 'Search criteria', 'Set search criteria');
    }

    public function CreateFormData()
    {
        $artistDao = new ArtistDao();
        $genreDao = new GenreDao();
        
        $artists = $artistDao->GetAll();
        $artistNames = [];
        foreach($artists as $artist){
            $artistNames[] = $artist->getName();
        }

        $genreDao = new GenreDao();
        
        $genres = $genreDao->GetAll();
        $genreNames = [];
        foreach($genres as $genre){
            $genreNames[] = $genre->getName();
        }

        $formData = [
        'Title'  => ['label' => 'Title', 'type' => 'text', 'placeholder' => 'Title', 'validations' => ["onlyNumbersAndCharacters"]],
        'Description'  => ['label' => 'Description', 'type' => 'text', 'placeholder' => 'Description', 'validations' => ["onlyNumbersAndCharacters"]],
        'Artists'  => ['label' => 'Artists', 'type' => 'checkbox', 'options' => $artistNames, 'multiple' => 'multiple','validations' => ["validOption"]],
        'Genres'  => ['label' => 'Genres', 'type' => 'checkbox', 'options' => $genreNames, 'multiple' => 'multiple','validations' => ['validOption']],
        'MinPrice'  => ['label' => 'Minimum price', 'type' => 'number', 'placeholder' => '0.0', 'step'=> '0.01', 'validations' => ["twoDecimals", "min:0"]],
        'MaxPrice'  => ['label' => 'Maximum price', 'type' => 'number', 'placeholder' => '0.0', 'step'=> '0.01', 'validations' => ["twoDecimals", "min:0"]],
        'MinYear' => ['label' => 'Minimum year', 'type' => 'number', 'placeholder' => '0000', 'step'=> '1', 'validations' => ["fullNumber", "min:0"]],
        'MaxYear' => ['label' => 'Maximum year', 'type' => 'number', 'placeholder' => '0000', 'step'=> '1', 'validations' => ["fullNumber", "min:0"]],
        'Type' => ['label' => 'Type', 'type' =>'checkbox', 'options' => ITEM_TYPES, 'multiple' => 'multiple','validations'=> ['validOption']],
        ];

        return $formData;
    }

    private function CreateFormResultsForSearchCriteria()
    {
        foreach($_SESSION['user_search_criteria'] as $key => $value)
        {
            $this->formResults[$key]['value'] = $value;
        }
        
    }
}