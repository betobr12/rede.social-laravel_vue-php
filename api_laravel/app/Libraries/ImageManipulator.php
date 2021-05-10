<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;


class ImageManipulator
{
    public $image, $id, $image_name, $directory;

    public function pathImageCreate(){



        if (isset($this->image)) {
            $currentDate = \Carbon\Carbon::now()->toDateString();
            $ext = substr($this->image, 11, strpos($this->image, ';') - 11);
            $file = str_replace('data:image/'.$ext.';base64,','',$this->image);
            $imageName = $this->id.'-'.$currentDate.'-'.uniqid().'.'.$ext;
            if (!Storage::disk('public')->exists($this->directory)) {
                Storage::disk('public')->makeDirectory($this->directory);
            }//
            if (Storage::disk('public')->exists($this->directory.'/'.$this->image_name)) {
                Storage::disk('public')->delete($this->directory.'/'.$this->image_name);
            }
            Storage::disk('public')->put($this->directory.'/'.$imageName, base64_decode($file));
            return (object) array("image_name" => $imageName);
        } else {
           return $imageName = $this->image_name;
        }
    }
    public function pathImageCreateOld(){

        if (isset($this->image)) {
            $time = time();
           // $diretory_imagem = $this->directory.DIRECTORY_SEPARATOR.$this->id_directory.$this->id;
            $diretory_imagem = $this->directory.'/'.$this->id_directory.$this->id;
            $ext = substr($this->image, 11, strpos($this->image, ';') - 11);
           // $url_image = $diretory_imagem.DIRECTORY_SEPARATOR.$time.'.'.$ext;
            $url_image = $diretory_imagem.'/'.$time.'.'.$ext;
            $file = str_replace('data:image/'.$ext.';base64,','',$this->image);
            $file = base64_decode($file);

            if (!file_exists($this->directory)) {
              mkdir($this->directory,0700);
            }

            if($this->image_data_exists){
                if(file_exists($this->image_data_exists)){
                  unlink($this->image_data_exists);
                }
            }

            if (!file_exists($diretory_imagem)) {
              mkdir($diretory_imagem,0700);
            }
            file_put_contents($url_image,$file);
            $user_image = $this->server.$url_image;

            return $user_image;
        }
    }


}
