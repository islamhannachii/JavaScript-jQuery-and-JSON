<?php
function validatePos()
    {
        for($i=1; $i<=9; $i++)
        {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;
            
            $year = htmlentities($_POST['year'.$i]);
            $desc = htmlentities($_POST['desc'.$i]);
            if ( strlen($year) == 0 || strlen($desc) == 0 )
            {
                return "All fields are required";
            }                        
            if ( ! is_numeric($year))
            {
                return "Position year must be numeric";
            }
        }    
        return 1;
    }
function validateEdu()
    {
            for($i=1; $i<=9; $i++)
            {
                if ( ! isset($_POST['edu_year'.$i]) ) continue;
                if ( ! isset($_POST['edu_school'.$i]) ) continue;                                    
                $edu_school = htmlentities($_POST['edu_school'.$i]);
                $edu_year = htmlentities($_POST['edu_year'.$i]);                                
                if (strlen($edu_year) == 0 || strlen($edu_school) == 0 )
                {
                    return "All fields are required";
                }                        
                if(! is_numeric($edu_year))
                {
                    return "Education year must be numeric";
                }
            }
            return 1;
    }