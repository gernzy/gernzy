<?php

namespace Gernzy\Server\Database\Seeds;

use Gernzy\Server\Actions\Helpers\Attributes;
use Gernzy\Server\Models\Image;
use Gernzy\Server\Models\Product;
use Gernzy\Server\Models\ProductFixedPrice;
use Gernzy\Server\Models\Tag;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            $this->helper();
        }
    }

    public function helper()
    {
        $faker = \Faker\Factory::create();
        $rand = rand(0, 20);

        $product = new Product([
            'title' => $faker->word(),
            'price_cents' =>  $faker->numberBetween($min = 1000, $max = 9000),
            'price_currency' => "USD",
            'short_description' => $faker->sentence(),
            'long_description' => $faker->paragraph(),
            'status' => $rand > 5 ? 'IN_STOCK' : 'OUT_OF_STOCK',
            'published' => $rand > 5 ? 1 : 0
        ]);

        /**
         * it is quite an expensive process seeding product variants, thus just assigning a random parent_id to the product
         * to simulate variant
         * */
        $product->parent_id = $rand;

        $product->save();

        // Product categories
        $categories = $faker->words();

        $createCategories = [];
        foreach ($categories as $category) {
            $createCategories[] = [
                'title' => $category
            ];
        }

        $product->categories()->createMany($createCategories);

        // Product attributes
        $meta =  [
            [
                'key' => $faker->word(),
                'value' => $faker->sentence()
            ],
            [
                'key' => $faker->word(),
                'value' => $faker->sentence()
            ]
        ];

        $sizes =  [
            [
                'size' => $faker->randomDigitNotNull,
            ],
            [
                'size' => $faker->randomDigitNotNull,
            ]
        ];

        $dimensions = [
            'width' => $faker->randomDigitNotNull,
            'height' => $faker->randomDigitNotNull,
            'length' => $faker->randomDigitNotNull,
            'unit' =>  $faker->word(),
        ];

        $weight = [
            'weight' => $faker->randomDigitNotNull,
            'unit' => $faker->word(),
        ];

        $prices = [
            [
                'currency' => $faker->currencyCode,
                'value' =>  $faker->randomDigitNotNull
            ],
            [
                'currency' => $faker->currencyCode,
                'value' =>  $faker->randomDigitNotNull
            ]
        ];


        $attributes = new Attributes();
        $attributes
            ->meta($meta)
            ->sizes($sizes)
            ->dimensions($dimensions)
            ->weight($weight)
            ->prices($prices);

        $product->attributes()->createMany(
            $attributes->toArray()
        );

        // images
        $images = $product->images()->saveMany([
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'jpeg']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'png']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'giff']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'jpeg']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'png']),
        ]);

        $image = $images[0];
        $attributes->featuredImage($image);
        $product->attributes()->createMany(
            $attributes->toArray()
        );

        // Tag product
        for ($i = 0; $i < 5; $i++) {
            $tag = Tag::create([
                'name' => $faker->word()
            ]);

            $product->addTag($tag);
            $product->save();
        }

        // Create fixed prices
        $product->fixedPrices()->saveMany([
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '44.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '1430.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '23.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '55.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '76.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '98.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '140.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '12.99',])
        ]);

        // Create variants for product
        // $this->createVariant($product->id);
        // $this->createVariant($product->id);
    }

    public function createVariant($parent_id)
    {
        $faker = \Faker\Factory::create();
        $rand = rand(0, 20);

        $product = new Product([
            'title' => $faker->word(),
            'price_cents' =>  $faker->numberBetween($min = 1000, $max = 9000),
            'price_currency' => "USD",
            'short_description' => $faker->sentence(),
            'long_description' => $faker->sentence(),
            'status' => $rand > 5 ? 'IN_STOCK' : 'OUT_OF_STOCK',
            'published' => $rand > 5 ? 1 : 0
        ]);

        $product->parent_id = $parent_id;


        $product->save();

        // Product categories
        $categories = $faker->words();

        $createCategories = [];
        foreach ($categories as $category) {
            $createCategories[] = [
                'title' => $category
            ];
        }

        $product->categories()->createMany($createCategories);

        // Product attributes
        $meta =  [
            [
                'key' => $faker->word(),
                'value' => $faker->sentence()
            ],
            [
                'key' => $faker->word(),
                'value' => $faker->sentence()
            ]
        ];

        $sizes =  [
            [
                'size' => $faker->randomDigitNotNull,
            ],
            [
                'size' => $faker->randomDigitNotNull,
            ]
        ];

        $dimensions = [
            'width' => $faker->randomDigitNotNull,
            'height' => $faker->randomDigitNotNull,
            'length' => $faker->randomDigitNotNull,
            'unit' =>  $faker->word(),
        ];

        $weight = [
            'weight' => $faker->randomDigitNotNull,
            'unit' => $faker->word(),
        ];

        $prices = [
            [
                'currency' => $faker->currencyCode,
                'value' =>  $faker->randomDigitNotNull
            ],
            [
                'currency' => $faker->currencyCode,
                'value' =>  $faker->randomDigitNotNull
            ]
        ];


        $attributes = new Attributes();
        $attributes
            ->meta($meta)
            ->sizes($sizes)
            ->dimensions($dimensions)
            ->weight($weight)
            ->prices($prices);

        $product->attributes()->createMany(
            $attributes->toArray()
        );

        // images
        $images = $product->images()->saveMany([
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'jpeg']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'png']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'giff']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'jpeg']),
            new Image(["name" => $faker->word(), "url" => $faker->url, "type" => 'png']),
        ]);

        $image = $images[0];
        $attributes->featuredImage($image);
        $product->attributes()->createMany(
            $attributes->toArray()
        );

        // Tag product
        for ($i = 0; $i < 5; $i++) {
            $tag = Tag::create([
                'name' => $faker->word()
            ]);

            $product->addTag($tag);
            $product->save();
        }

        // Create fixed prices
        $product->fixedPrices()->saveMany([
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '44.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '1430.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '23.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '55.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '76.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '98.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '140.99',]),
            new ProductFixedPrice(['country_code' => $faker->currencyCode, 'price' => '12.99',])
        ]);

        return $product;
    }
}
