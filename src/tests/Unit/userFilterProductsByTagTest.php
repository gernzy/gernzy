<?php

use Lab19\Cart\Testing\TestCase;
use Lab19\Cart\Models\Product;
use Lab19\Cart\Models\Tag;

/**
 * @group Products
 */
class TestFilterProducts extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        // $this->availableCount = 11;

        // factory(Product::class, $this->availableCount)->create()->each(function ($product) {
        //     $product->status = 'IN_STOCK';
        //     $product->title = 'Coffee pod';
        //     $product->published = 1;
        //     $product->save();
        // });

        // factory(Product::class, $this->availableCount + 10)->create()->each(function ($product) {
        //     $product->status = 'OUT_OF_STOCK';
        //     $product->save();
        // });


        // factory(Tag::class, 20)->create()->each(function ($tag) {
        //     $tag->save();
        // });

    }

    public function testUserShouldBeAbleToFilterProductsByTag(): void
    {

        $tag = factory(Tag::class)->create();
        $tag->save();

        $product = factory(Product::class)->create();
        $product->save();

        $product->tag($tag);

        $response = $this->graphQL('
                query {
                    tag(id: 1) {
                        products {
                            id
                            title
                            short_description
                        }
                    }
                }
            ');

        $response->assertDontSee('errors');

        $result = $response->decodeResponseJson();

        print json_encode($result);

        $this->assertCount(3, $result['data']['tag']['products'][0]);

        $this->assertTrue($product->tags->contains('id', $tag->id));

        $response->assertJsonStructure([
            'data' => [
                'tag' => [
                    'products' => [
                        ['id', 'title', 'short_description']
                    ]
                ]
            ]
        ]);
    }

    
    public function testUserShouldBeAbleToFilterForTaggable(): void
    {
        $creatQuantity = 10;

        factory(Tag::class, $creatQuantity)->create()->each(function ($tag) {
            $tag->save();
        });

        factory(Product::class, $creatQuantity)->create()->each(function ($product) {
            $product->tag(1);
            $product->save();
        });


        $product = Product::find(1);
        $tag = Tag::find(1);


        $response = $this->graphQL('
                query {
                    tag(id: 1) {
                        products {
                            id
                            title
                            short_description
                        }
                    }
                }
            ');

        $response->assertDontSee('errors');

        $result = $response->decodeResponseJson();

        print json_encode($result);

        $this->assertCount($creatQuantity, $result['data']['tag']['products']);

        $this->assertTrue($product->tags->contains('id', $tag->id));

        $response->assertJsonStructure([
            'data' => [
                'tag' => [
                    'products' => [
                            ['id', 'title', 'short_description']
                    ]
                ]
            ]
        ]);
    }
}
