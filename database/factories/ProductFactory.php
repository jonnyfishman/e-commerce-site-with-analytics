<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Models\Product;

use ColorCompare\Color;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    public $brand_and_names = [
      'Nike'=>
        ['Sky','Sky Plus','Adventurer','Speed','Pacer'],
      'Salomon'=>
        ['Trail 21','Trail 22','Elite','Classic'],
      'Cloud'=>
        ['Up','Pegasus','Space','Space Limited Edition'],
      'Brooks'=>
        ['Caldera','Vapor','Vapor GTS'],
      'Hoka'=>
        ['Extreme','Hierro','Extreme v2'],
      'Hi-Tec'=>
        ['Zoom'],
      'adidas'=>
        ['Adrenaline','Supersonic','Adrenaline X'],
      'Altra'=>
        ['Terra','Peregrine','Peregine H2','Terra H2'],
      'Asics'=>
        ['Ultra','Ultra X','Ultra GTS'],
      'Inov8'=>
        ['Lone Peak','Speedgoat'],
      'New Balance'=>
        ['Hardtail','Hardtail Limted']
    ];

    protected $text = [
      'Designed from the ground up for a new season.',
      'Our runners at BRAND have tested these at top events from the trail running calendar.',
      'Designed for stride-style runners.',
      'Their design allows runners to conserve energy.',
      'Comfort and fit are excellent.'
    ];

    protected function createTrainer($trainer_body, $filename) {

      list($mr, $mg, $mb) = sscanf($this->palette_m[random_int(0, count($this->palette_m)-1)], "#%02x%02x%02x");
      list($tr, $tg, $tb) = sscanf($this->palette_t[$trainer_body], "#%02x%02x%02x");

    $sole = rand(0, 1) ? imageCreateFromPng(resource_path().'/GD/sole.png') : imageCreateFromPng(resource_path().'/GD/transparent.png');
    $fg = imageCreateFromPng(resource_path().'/GD/fg.png');
    $mid = imageCreateFromPng(resource_path().'/GD/mid.png');
    $logo = rand(0, 1) ? imageCreateFromPng(resource_path().'/GD/logo.png') : imageCreateFromPng(resource_path().'/GD/transparent.png');

    imageAlphaBlending($mid, false);
    imagetruecolortopalette($mid, false, 255);

    imagecolorset($mid, imagecolorclosest($mid, 255, 0, 255), $mr, $mg, $mb);
    imagesavealpha($mid, true);
    imagecolortransparent($mid, imagecolorat($mid,0,0));

    $final_img = imagecreatetruecolor(400, 400);
    imageAlphaBlending($final_img, true);
    imageSaveAlpha($final_img, true);
    //create new image and fill with background color
    $backgroundImg = imagecreatetruecolor(400, 400);
    $color = imagecolorallocate($backgroundImg, $tr, $tg, $tb);
    imagefill($backgroundImg, 0, 0, $color);

    //copy original image to background
    imagecopy($final_img, $backgroundImg, 0, 0, 0, 0, 400, 400);
    imagecopy($final_img, $mid, 0, 0, 0, 0, 400, 400);

    imagecopy($final_img, $fg, 0, 0, 0, 0, 400, 400);

    imagecopy($final_img, $logo, 0, 0, 0, 0, 400, 400);

    imagecopy($final_img, $sole, 0, 0, 0, 0, 400, 400);

    echo 'Generating Image...'.$filename. '_400x400.png' .PHP_EOL;
    echo 'Generating Image...'.$filename. '_200x200.jpg' .PHP_EOL;
    if ( rand(0, 1) ) {
      imageflip($final_img, IMG_FLIP_HORIZONTAL);
    }

    imagePng($final_img, storage_path().'/app/public/product_files/'. $filename . '_400x400.png');
    $thumbnail = imagecreatetruecolor(200,200);
    imagecopyresampled($thumbnail, $final_img, 0, 0, 0, 0, 200, 200, 400, 400);
    imagejpeg($thumbnail, storage_path().'/app/public/product_files/'. $filename . '_200x200.jpg',90);

    imagedestroy($final_img);

      if ( file_exists(storage_path().'/app/public/product_files/'. $filename . '_400x400.png') ) {
        return round( ( new color(['r'=>$tr, 'g'=>$tg, 'b'=>$tb]) )->getHsl()['l'], 2);
      }
      else {
        return false;
      }
    }

    protected $palette_m = ['#b6ddee','#11666d','#fcbe4d','#884187','#7ea0bb','#c63463','#876c59','#fbc8c4','#04599c','#cb3527','#000103','#C7F2A7','#44CF6C','#A9FDAC'];
    protected $palette_t = ['Space Cadet'=>'#262e57', 'Orange Web'=>'#f7a007', 'French Raspberry'=>'#c32b42', 'Platinum'=>'#e8e9eb', 'Celadon Green'=>'#36827f', 'Maximum Yellow'=>'#F6F930', 'Fawn'=>'#E6AA68', 'Metallic Seaweed'=>'#1F7A8C', 'Bright Yellow Crayola'=>'#FCAB10','Safety Orange'=>'#F17105', 'Eerie Black'=>'#1B1B1E','Gainsboro'=>'#D8DBE2','Minion Yellow'=>'#EAD94C','Tea Green'=>'#D7EBBA','Camel'=>'#BB9457','Dark Purple'=>'#291528','Artic Blue'=>'#F4FAFF'];
    // createTrainer($palette_m[random_int(0,count($palette_m)-1)],$palette_t[random_int(0,count($palette_t)-1)]);
    protected $sorting_palette = ['#000000','#006699','#FFFF33','#CCCCFF','#339966','#cb3527','#ff6633','#f0f0f0'];

    protected function closestMatch($colour) {
      $original_colour = new Color($colour);
      $closest = null;
      $closest_difference = 10000;

      foreach( $this->sorting_palette as $colour_index) {
          $difference = $original_colour->getDifference(new Color($colour_index));
          if ($closest_difference > $difference ) {
            $closest_difference = $difference;
            $closest = $colour_index;
          }

      }
      return $closest;

    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $brand = Arr::random( array_keys($this->brand_and_names) );

        if ( count($this->brand_and_names[$brand]) == 0) {
          $name = fake()->streetSuffix();
        } else {
          $name = array_splice( $this->brand_and_names[$brand], random_int(0, count($this->brand_and_names[$brand]) - 1 ), 1)[0];
        }

        $sentences = Arr::shuffle($this->text);
        $number_of_sentences = count($this->text);

        $description = array_slice( $sentences, 0, random_int(1, $number_of_sentences ) );

        $description = implode( ' ', $description );

        $description = str_replace( "BRAND", $brand, $description );

        $colour = Arr::random( array_keys($this->palette_t) );
        $hex = $this->palette_t[$colour];
        // $image_name = bin2hex(random_bytes(2)) . '_' . str_replace(' ', '_', $name);
        $image_name = Str::random(4) . '_' . str_replace(' ', '_', $name);

        $colour_index = $this->createTrainer($colour, $image_name);
        if ( !$colour_index ) {
            $colour = 'Colours Unavailable';
            $image_name = null;
        }




        return [
            'name' => $name,
            'brand' => $brand,
            'price' => random_int(87,156) . '.99',
            'description' => $description,
            'colour' => json_encode(['index'=>$colour_index, 'name'=>$colour, 'hex'=>$this->closestMatch($hex)]),
            'image' => $image_name
        ];
    }
}
