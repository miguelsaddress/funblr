<?php

namespace Funblr\Http\Requests;

use Funblr\Http\Requests\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Intervention\Image\Facades\Image;

class CreatePostRequest extends Request
{
    /**
     * Constructor. Creates a CreatePostRequest
     * Extended to add custom validator for resolution
     * 
     * Illuminate\Validation\Factory $validationFactory
     */
     
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend(
            'resolution',
            function ($attribute, $value, $parameters) {
                // $parameters[0] could look like this 1280x1024
                $resX = explode("x", $parameters[0])[0];
                $resY = explode("x", $parameters[0])[1];
                $img = Image::make($value);
                return $img->height() <= $resY  && $img->width() <= $resX;
            },
            'Wrong resolution!'
        );

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /* jpeg, gif and png, maximumsize 20MB and dimensions 1920x1080 */
            'image' => 'required|resolution:1920x1080|mimes:jpeg,png,gif|max:20000',
            'title' => 'max: 100'
        ];
    }
    
    /**
     * Get the error messages that apply per failure type
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'resolution'    => 'Resolution must be 1920x1080 maximum',
            'mimes'         => 'Accepted files: png, gif and jpeg',
            'max'           => 'max size is 20MB',
        ];
    }
}
