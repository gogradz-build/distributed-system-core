<?php 
    use App\Models\Student;

    if(!function_exists('getStudentId')){
       function getStudentId($student_reg){
            $student_id = Student::where('register_number',$student_reg)
                      ->value('id'); 
            return $student_id;             
       } 
    }
?>