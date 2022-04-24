<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ProductCategories;
use App\Models\Products;
use App\Models\ProductShortSpecifications;
use App\Models\ProductSpecificationCategories;
use App\Models\ProductSpecifications;
use App\Models\Offers;
use App\Models\Deals;
use Goutte;

class ProductsController extends Controller
{
    public function createProducts()
    {

        try {
        $categories = Http::withHeaders([
            'Fk-Affiliate-Id' => 'budhadal',
            'Fk-Affiliate-Token' => 'a0be886d04344d979ef24bb89f1598f4',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get('https://affiliate-api.flipkart.net/affiliate/api/budhadal.json');


        function addProducts($flipkartProductsJson, $categoryId)
        {
            foreach ($flipkartProductsJson as $key => $product) {
                if ($product['productBaseInfoV1']['flipkartSellingPrice']['amount'] > 9999 && $product['productBaseInfoV1']['inStock'] == true) {
                    $productStore = new Products();
                    $productStore->product_category_id = $categoryId;
                    $productStore->title = $product['productBaseInfoV1']['title'];
                    $productStore->slug = str_slug($product['productBaseInfoV1']['title']);
                    $productStore->product_description = $product['productBaseInfoV1']['productDescription'];
                    $productStore->image_url_200x200 = $product['productBaseInfoV1']['imageUrls']['200x200'];
                    $productStore->image_url_400x400 = $product['productBaseInfoV1']['imageUrls']['400x400'];
                    $productStore->image_url_800x800 = $product['productBaseInfoV1']['imageUrls']['800x800'];
                    $productStore->fk_pid = $product['productBaseInfoV1']['productId'];
                    $productStore->fk_selling_price = $product['productBaseInfoV1']['flipkartSellingPrice']['amount'];
                    $productStore->fk_selling_currency = $product['productBaseInfoV1']['flipkartSellingPrice']['currency'];
                    $productStore->fk_product_url = $product['productBaseInfoV1']['productUrl'];
                    $productStore->product_brand = $product['productBaseInfoV1']['productBrand'];
                    $productStore->fk_in_stock = $product['productBaseInfoV1']['inStock'];
                    $productStore->save();

                    $productId = $productStore->id;

                    if(isset($product['categorySpecificInfoV1']['detailedSpecs'])){
                        foreach ($product['categorySpecificInfoV1']['detailedSpecs'] as $key => $shortSpecs) {
                            $ProductShortSpecification = new ProductShortSpecifications();
                            $ProductShortSpecification->product_id = $productId;
                            $ProductShortSpecification->title = $shortSpecs;
                            $ProductShortSpecification->save();
                        }
                    }

                    if (isset($product['categorySpecificInfoV1']['specificationList'])) {
                        foreach ($product['categorySpecificInfoV1']['specificationList'] as $key => $specificationList) {
                            $ProductSpecificationCategory = new ProductSpecificationCategories();
                            $ProductSpecificationCategory->product_id = $productId;
                            $ProductSpecificationCategory->title = $specificationList['key'];
                            $ProductSpecificationCategory->save();

                            $ProductSpecificationCategoryId = $ProductSpecificationCategory->id;

                            if (isset($specificationList['values'])) {
                                foreach ($specificationList['values'] as $key => $detailSpecification) {
                                    $ProductSpecification = new ProductSpecifications();
                                    $ProductSpecification->product_specification_category_id = $ProductSpecificationCategoryId;
                                    $ProductSpecification->title = $detailSpecification['key'];
                                    $ProductSpecification->description = $detailSpecification['value'][0];
                                    $ProductSpecification->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        function nextUrlData($url, $categoryId)
        {
            $flipkartProducts = Http::withHeaders([
                'Fk-Affiliate-Id' => 'budhadal',
                'Fk-Affiliate-Token' => 'a0be886d04344d979ef24bb89f1598f4',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->get($url);

            if ($flipkartProducts->successful()) {
                $flipkartProductsJson = $flipkartProducts['products'];
    
                addProducts($flipkartProductsJson, $categoryId);

                // foreach ($flipkartProductsJson as $key => $product) {
                //     if ($product['productBaseInfoV1']['flipkartSellingPrice']['amount'] > 9999 && $product['productBaseInfoV1']['inStock'] == true) {
                //         $productStore = new Products();
                //         $productStore->product_category_id = $categoryId;
                //         $productStore->title = $product['productBaseInfoV1']['title'];
                //         $productStore->slug = str_slug($product['productBaseInfoV1']['title']);
                //         $productStore->product_description = $product['productBaseInfoV1']['productDescription'];
                //         $productStore->image_url_200x200 = $product['productBaseInfoV1']['imageUrls']['200x200'];
                //         $productStore->image_url_400x400 = $product['productBaseInfoV1']['imageUrls']['400x400'];
                //         $productStore->image_url_800x800 = $product['productBaseInfoV1']['imageUrls']['800x800'];
                //         $productStore->fk_pid = $product['productBaseInfoV1']['productId'];
                //         $productStore->fk_selling_price = $product['productBaseInfoV1']['flipkartSellingPrice']['amount'];
                //         $productStore->fk_selling_currency = $product['productBaseInfoV1']['flipkartSellingPrice']['currency'];
                //         $productStore->fk_product_url = $product['productBaseInfoV1']['productUrl'];
                //         $productStore->product_brand = $product['productBaseInfoV1']['productBrand'];
                //         $productStore->fk_in_stock = $product['productBaseInfoV1']['inStock'];
                //         $productStore->save();

                //         $productId = $productStore->id;

                //         if (isset($product['categorySpecificInfoV1']['detailedSpecs'])) {
                //             foreach ($product['categorySpecificInfoV1']['detailedSpecs'] as $key => $shortSpecs) {
                //                 // $ProductShortSpecification = new ProductShortSpecifications();
                //                 // $ProductShortSpecification->product_id = $productId;
                //                 // $ProductShortSpecification->title = $shortSpecs;
                //                 // $ProductShortSpecification->save();
                //             }
                //         }

                //     if (isset($product['categorySpecificInfoV1']['specificationList'])) {
                //         foreach ($product['categorySpecificInfoV1']['specificationList'] as $key => $specificationList) {
                //             // $ProductSpecificationCategory = new ProductSpecificationCategories();
                //             // $ProductSpecificationCategory->product_id = $productId;
                //             // $ProductSpecificationCategory->title = $specificationList['key'];
                //             // $ProductSpecificationCategory->save();

                //             // $ProductSpecificationCategoryId = $ProductSpecificationCategory->id;

                //             if (isset($specificationList['values'])) {
                //                 foreach ($specificationList['values'] as $key => $detailSpecification) {
                //                     // $ProductSpecification = new ProductSpecifications();
                //                     // $ProductSpecification->product_specification_category_id = $ProductSpecificationCategoryId;
                //                     // $ProductSpecification->title = $detailSpecification['key'];
                //                     // $ProductSpecification->description = $detailSpecification['value'][0];
                //                     // $ProductSpecification->save();
                //                 }
                //             }
                //         }
                //     }
                //     }
                // }

                $nextUrlLoop = $flipkartProducts['nextUrl'];

                if($nextUrlLoop) {
                    nextUrlData($nextUrlLoop, $categoryId);
                }
                else{
                    return 'Server Error 1';
                }
            }

            if ($flipkartProducts->serverError()) {
                return 'Server Error 2';
            }
        }

        $response = $categories['apiGroups']['affiliate']['apiListings'];

        foreach ($response as $key => $data) {
            $name = $data['availableVariants']['v1.1.0']['resourceName'];
            $slug = $data['apiName'];
            $products_url = $data['availableVariants']['v1.1.0']['get'];

            // return $name;

            set_time_limit(0);

            $category = new ProductCategories();
            $category->name = $name;
            $category->slug = $slug;
            $category->products_url = $products_url;
            $category->save();

            $categoryId = $category->id;

            if ($name == 'laptops') {

                $flipkartProducts = Http::withHeaders([
                    'Fk-Affiliate-Id' => 'budhadal',
                    'Fk-Affiliate-Token' => 'a0be886d04344d979ef24bb89f1598f4',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->get($products_url);

                $nextUrl = $flipkartProducts['nextUrl'];

                $flipkartProductsJson = $flipkartProducts['products'];


                addProducts($flipkartProductsJson, $categoryId);
          
                if($nextUrl) {
                    nextUrlData($nextUrl, $categoryId);
                }
            }
        }
    } catch (\Exception $e) {
        return  $e->getMessage();
    }

        return 'All Done';
    }

    public function createOffers()
    {
        try {
            $offers = Http::withHeaders([
            'Fk-Affiliate-Id' => 'budhadal',
            'Fk-Affiliate-Token' => 'a0be886d04344d979ef24bb89f1598f4',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get('https://affiliate-api.flipkart.net/affiliate/offers/v1/all/json');

            $response = $offers['allOffersList'];

            foreach ($response as $key => $data) {
                set_time_limit(0);

                $offer = new Offers();
                $offer->title = $data['title'];
                $offer->name = $data['name'];
                $offer->start_time = $data['startTime'];
                $offer->end_time = $data['endTime'];
                $offer->description = $data['description'];
                $offer->fk_url = $data['url'];
                $offer->image_default = $data['imageUrls'][0]['url'];
                $offer->image_low = $data['imageUrls'][1]['url'];
                $offer->image_mid = $data['imageUrls'][2]['url'];
                $offer->image_high = $data['imageUrls'][3]['url'];
                $offer->save();
            }
        } catch (\Exception $e) {
            return  $e->getMessage();
        }

        return  'Success';
    }

    public function createDeals()
    {
        try {
            $deals = Http::withHeaders([
            'Fk-Affiliate-Id' => 'budhadal',
            'Fk-Affiliate-Token' => 'a0be886d04344d979ef24bb89f1598f4',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get('https://affiliate-api.flipkart.net/affiliate/offers/v1/dotd/json');

            $response = $deals['dotdList'];

            foreach ($response as $key => $data) {
                set_time_limit(0);

                $deal = new Deals();
                $deal->title = $data['title'];
                $deal->name = $data['name'];
                $deal->description = $data['description'];
                $deal->fk_url = $data['url'];
                $deal->image_default = $data['imageUrls'][0]['url'];
                $deal->image_low = $data['imageUrls'][1]['url'];
                $deal->image_mid = $data['imageUrls'][2]['url'];
                $deal->image_high = $data['imageUrls'][3]['url'];
                $deal->save();
            }
        } catch (\Exception $e) {
            return  $e->getMessage();
        }

        return  'Success';
    }

    public function products(Request $request)
    {
        $search = $request->query('search');

        if (!$search) {
            $products = Products::inRandomOrder()->limit(50)->get();
        }

        if ($search) {
            $products = Products::whereLike('title', $search)->get();
        }
        return $products;
    }

    public function product($slug)
    {
        return Products::with('shortSpecifications', 'productSpecificationCategories', 'productSpecificationCategories.detailSpecifications')->where('slug', $slug)->first();
    }

    public function offers(Request $request)
    {
        $offers = Offers::inRandomOrder()->get();

        return $offers;
    }

    public function deals(Request $request)
    {
        $deals = Deals::inRandomOrder()->get();

        return $deals;
    }


    public function CategoryProducts(Request $request,$slug)
    {
        
        $products = Products::whereHas('category', function ($query) use($slug) {
            $query->where('slug', $slug);
        })->limit(50)->get();

        return $products;
    }

    public function categories(Request $request)
    {
        $ProductCategories = ProductCategories::inRandomOrder()->get();

        return $ProductCategories;
    }

    public function cronchek()
    {
        return 'okayyy';
    }


    public function getInstaUsers(Request $request){

        $str = $request->data;
        $words = preg_split('#\s+#', $str);

        // return response()->json($words);

        function startsWith ($string, $startString)
        {
            $len = strlen($startString);
            return (substr($string, 0, $len) === $startString);
        }

        foreach($words as $word){
            if(startsWith($word,"href=\"/")){
                $word = str_replace('"', '', $word);
                $word = str_replace('/', '', $word);
               $resultArray[] = str_replace('href=', '', $word);
            }
        }

        return response()->json(array_values(array_unique($resultArray)));
      
    }

    public function indiaTalentsCrawler(){

        $myarray = array();
        $crawler = Goutte::request('GET', 'https://indiantalents.in/category/biography/starzone/actress/');

        $pages = ($crawler->filter('.page-nav a')->count() > 0) ? $crawler->filter('.page-nav a.last')->text() : 0;

        for ($i = 1; $i < $pages + 1; $i++) {

            set_time_limit(0);

            $crawler = Goutte::request('GET', 'https://indiantalents.in/category/biography/starzone/actress/page/'.$i);

            $crawler->filter('.td-main-content-wrap .td-block-row')->each(function ($nodeIn) use (&$myarray) {
                $nodeIn->filter('.td-block-span6')->each(function ($nodeIn2) use (&$myarray) {

                    $crawlerInUrl = $nodeIn2->filter('.td-module-title a')->attr('href');

                    $crawlerIn = Goutte::request('GET', $crawlerInUrl);

                    $category = new ProductCategories();
                    $category->name = $crawlerIn->filter('.td-main-content .td-post-content')->html();
                    $category->slug = 'okk';
                    $category->products_url = $crawlerInUrl;
                    $category->save();

                    // array_push($myarray, $crawlerIn->filter('.td-main-content p')->text());
                });
            });

            if($i == 2){
                break;
            }

            // if($i == $pages){
            //     break;
            // }
        }

        return response()->json(($myarray));

        $crawler = Goutte::request('GET', 'https://www.pendujatt.net/single-tracks/punjabi-single-tracks-new.php');
        $crawler->filter('.list.song')->each(function ($node) {
            $crawlerIn = Goutte::request('GET', 'https://www.pendujatt.net'.$node->attr('href'));
            $crawlerIn->filter('.list.download')->each(function ($nodeIn) {
            dump($nodeIn->attr('href'));
            });
        });

        return 'okk';
        
        $crawler = Goutte::request('GET', 'https://indiantalents.in/category/biography/starzone/actress/');

        return $crawler;

            $crawler->filter('.td-main-content-wrap .td-block-row')->each(function ($nodeIn) {
                $nodeIn->filter('.td-block-span6')->each(function ($nodeIn2) {
                    dump($nodeIn2->filter('.td-module-title a')->attr('href'));
                     
                 });
            });

        return true;
    }

}
