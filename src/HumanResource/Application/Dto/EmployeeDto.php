<?php
namespace Src\HumanResource\Application\Dto;

class EmployeeDto
{
    public int $id;
    public string $name;
    public string $lastname;
    public string $full_name;
    public string $dni;
    public string $email;
    public $phone1;
    public string $state;
    public $cropped_image;
    public $cost_line_id;
    public $cost_line;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->lastname = $data['lastname'];
        $this->full_name = $data['full_name'];
        $this->dni = $data['dni'];
        $this->email = $data['email'];
        $this->phone1 = $data['phone1'];
        $this->state = $data['state'];
        $this->cropped_image = $data['cropped_image'];
        $this->cost_line_id = $data['cost_line_id'];
        $this->cost_line = $data['cost_line'];
    }
}
