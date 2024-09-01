<?php

namespace App\Models;

use App\Rules\ReturnDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BrokenCar extends Model
{
    use HasFactory;
    protected $table = 'broken_cars';
    protected $fillable  = [
        "user_id",
        "car_id",
        "start_date_broke",
        "description",
        "user_fault",
        "start_date_repair",
        "return_date",
    ];

    // Rules used when admin wants to write that car is broken
    public static function rules_start(array $requestData = []) :array
    {
        $rules = [
            'car_id' => ['required','exists:cars,id'],
            'start_date_broke' => ['required', 'date'],
            'description' => ['required', 'min:10', 'string'],
            'user_fault' => ['required', 'bool'],
        ];

        if($requestData['user_fault'] === true)
        {
            $rules['user_id'] = 'exists:users,id';
        }
        return $rules;
    }
    // Rules used when admin wants to write when car went for repairs
    public static function rules_medium(array $requestData = []) :array
    {
        return [
            'car_id' => ['exists:broken_cars,car_id'],
            'start_date_repair' => ['required', (new ReturnDate())->setData(["start_date"=> $requestData['start_date_broke']])]
        ];
    }
    // rules used when car is fixed
    public static function rules_end(array $requestData = []) :array
    {
        return [
            'car_id' => ['exists:cars,id'],
            'return_date' => function() use($requestData){
                $car = BrokenCar::where('car_id', $requestData['car_id'])->first();
                return (new ReturnDate())->setData([
                    'start_date' => $car->repair_date,
                ]);
            },

        ];
    }

}
