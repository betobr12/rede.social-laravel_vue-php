<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;


class ImageManipulator
{
    public $image, $id, $image_name, $directory, $server = 'http://127.0.0.1:8000/';
    //modificar server na produção
    public function pathImageCreate()
    {
        $ext_full = substr($this->image, 11, strpos($this->image, ';') - 11);

        switch ($ext_full){
            case 'jpg':
                $ext = $ext_full;
                break;
            case 'jpeg':
                $ext = $ext_full;
                break;
            case 'png':
                $ext = $ext_full;
                break;
            case 'svg':
                $ext = $ext_full;
                break;
            default:
            return false;
        }

        if (isset($this->image)) {

            $currentDate = \Carbon\Carbon::now()->toDateString();

            $file = str_replace('data:image/'.$ext.';base64,','',$this->image);
            $imageName = $this->id.'-'.$currentDate.'-'.uniqid().'.'.$ext;

            if (!Storage::disk('public')->exists($this->directory.'/'.$this->id)) {
                Storage::disk('public')->makeDirectory($this->directory.'/'.$this->id);
            }

            if (Storage::disk('public')->exists($this->directory.'/'.$this->id.'/'.$this->image_name)) {
                Storage::disk('public')->delete($this->directory.'/'.$this->id.'/'.$this->image_name);
            }

            $url =  $this->server.'storage/'.$this->directory.'/'.$this->id.'/'.$imageName;
            Storage::disk('public')->put($this->directory.'/'.$this->id.'/'.$imageName, base64_decode($file));
            return (object) array("image_name" => $imageName, "url" => $url);
        } else {
            return $imageName = $this->image_name;
        }
    }
    /*
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
    */
}
