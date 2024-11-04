<?php

namespace Database\Seeders;

use App\Models\Trader;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\GoldPrice;
use App\Models\Item;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Shop;

class DatabaseSeeder extends Seeder {
	protected $fakeNames = [ 
		"أحمد العلي",
		"فاطمة الزهراء",
		"محمد الشامي",
		"ليلى النجار",
		"يوسف الكردي",
		"سارة الحسن",
		"علي البغدادي",
		"نور الهادي",
		"خالد السعدي",
		"ريم العبدالله",
		"عمر الطيب",
		"هدى الكيلاني",
		"زين العابدين",
		"مريم الفاروق",
		"طارق الجبوري",
		"ندى الرفاعي",
		"سامي الحسين",
		"ياسمين العطار",
		"كريم الزهراني",
		"هبة الشافعي",
	];
	protected $fakeProducerName = [ 
		"شركة الذهب العربي",
		"مناجم الذهب الفاخرة",
		"الذهب اللامع المحدودة",
		"شركة الكنوز الذهبية",
		"ذهب الشرق الأوسط",
		"شركة مناجم النخبة",
		"كنز الخليج للذهب",
		"شركة الذهب الملكي",
		"معادن الذهب الثمينة",
		"ذهب الأهرامات",
		"شركة الذهب العريق",
		"مناجم الشمس الذهبية",
		"شركة المجوهرات الراقية",
		"الذهب النادر المحدودة",
		"شركة الفخامة الذهبية",
		"مناجم الفجر الذهبي",
		"شركة الذهب العالمي",
		"الذهب الأصفر المحدودة",
		"شركة المناجم اللامعة",
	];
	protected $fakeCategoryNames = [ 
		"خاتم ",
		"قلادة ",
		"سوار ",
		"تاج ",
		"دبوس ",
		"أقراط ",
		"مشبك ",
		"عقد ",
		"حزام ",
		"سلسلة ",
		"ساعة ",
		" فضة إبزيم ",
		"مشبك الشعر ",
		" فضة حلق ",
		"فضة بروش ",
		" فضة خلخال ",
		"سلسلة فضة",
		"مشبك فضة",
		"خاتم فاخر",
		" فضة عقد",
	];
	protected $fakeProductNames = [ 
		"الخاتم الملكي",
		"قلادة الفجر",
		"سوار الشمس",
		"تاج الأمل",
		"دبوس النجمة",
		"أقراط الجنة",
		"مشبك العقيق",
		"عقد القمر",
		"حزام الذهب",
		"سلسلة الياقوت",
		"ساعة الزمن",
		"إبزيم الفخامة",
		"مشبك الشعر الماسي",
		"حلق اللؤلؤ",
		"بروش الورود",
		"خلخال الحرير",
		"سلسلة الزمرد",
		"مشبك الماس",
		"خاتم الحب",
		"عقد الكنز",
	];
	protected $fakeGoldPriceStandards = [ 
		"24 عيار",
		"21 عيار",
		"18 عيار",
	];
	protected $fakeTraderName = [ 
		"أحمد العلي",
		"محمد الزهراني",
		"علي الشامي",
		"حسن النجار",
		"يوسف الكردي",
		"عمر الحسن",
		"خالد البغدادي",
		"إبراهيم السعدي",
		"فهد العبدالله",
		"عبد الله الطيب",
		"كريم الكيلاني",
		"سامي العطار",
		"هشام الفاروق",
		"محمود الجبوري",
		"طارق الرفاعي",
		"مصطفى الحسين",
		"زياد العطار",
		"ياسر الزهراني",
		"أيمن الشافعي",
	];
	protected $fakeDescription =
		"تُعد مجوهرات الذهب العتيقة الخيار الأمثل لمحبي الفخامة. خاتم النقاء المصنوع من 24 قيراطًا وسلسلة الأمل المذهبة تعكسان بريق الذهب الحقيقي. أضف لمسة من الأناقة مع سوار الفجر وقلادة الشروق الفاخرة لتكمل إطلالتك.";
	protected $fakeShopNames = [ 
		"كنوز الذهبي",
		"مجوهرات الفخامة",
		"دار الذهب اللامع",
	];
	protected $fakeAddress = [ 
		"شارع النيل، حي الزمالك، مدينة القاهرة",
		"شارع الهرم، حي الجيزة، مدينة الجيزة",
		"شارع الكورنيش، حي المعادي، مدينة القاهرة",
		"شارع الثورة، حي مصر الجديدة، مدينة القاهرة",
		"شارع البحر، حي المنشية، مدينة الإسكندرية",
		"شارع الجمهورية، حي وسط البلد، مدينة القاهرة",
		"شارع الجيش، حي باب الشعرية، مدينة القاهرة",
		"شارع الأزهر، حي الحسين، مدينة القاهرة",
		"شارع بورسعيد، حي السيدة زينب، مدينة القاهرة",
		"شارع صلاح سالم، حي مدينة نصر، مدينة القاهرة",
		"شارع البحر الأعظم، حي الجيزة، مدينة الجيزة",
		"شارع النصر، حي المعادي الجديدة، مدينة القاهرة",
		"شارع الملك فيصل، حي الهرم، مدينة الجيزة",
		"شارع عبد العزيز، حي وسط البلد، مدينة القاهرة",
		"شارع محمد علي، حي الحلمية، مدينة القاهرة",
		"شارع القصر العيني، حي جاردن سيتي، مدينة القاهرة",
		"شارع التحرير، حي الدقي، مدينة الجيزة",
		"شارع رمسيس، حي العباسية، مدينة القاهرة",
		"شارع مصطفى كامل، حي سموحة، مدينة الإسكندرية",
		"شارع النزهة، حي مصر الجديدة، مدينة القاهرة",
	];
	/**
	 * Seed the application's database.
	 */
	public function run(): void {
		User::create( [ 
			'name' => 'admin',
			'email' => 'admin@gmail.com',
			'role' => 'admin',
			'password' => '123456789',
		] );
		for ( $i = 0; $i < 3; $i++ ) {
			Shop::create( [ 
				'name' => $this->fakeShopNames[ $i ],
			] );
		}
		for ( $i = 0; $i < count( $this->fakeGoldPriceStandards ); $i++ ) {
			GoldPrice::create( [ 
				'standard' => $this->fakeGoldPriceStandards[ $i ],
				'description' => $this->fakeDescription,
				'price' => fake()->numberBetween( 10000, 15000 ),
			] );
		}
		for ( $i = 0; $i < 19; $i++ ) {
			Category::create( [ 
				'name' => $this->fakeCategoryNames[ $i ],
			] );
			Producer::create( [ 
				'name' => $this->fakeProducerName[ $i ],
			] );
			Trader::create( [ 
				'name' => $this->fakeTraderName[ $i ],
				'phone' => fake()->phoneNumber(),
				'address' => $this->fakeAddress[ $i ],
				'money_balance' => fake()->numberBetween( 10000, 1000000 ),
				'gold_balance' => fake()->numberBetween( 10000, 1000000 ),
			] );
			Product::create( [ 
				'category_id' => $i + 1,
				'producer_id' => $i + 1,
				'trader_id' => $i + 1,
				'shop_id' => fake()->randomElement( [ 1, 2, 3 ] ),
				// 'invoice_id'=>$this->,
				'gold_price_id' => fake()->randomElement( [ 1, 2, 3 ] ),
				'code' => "code $i",
				'name' => $this->fakeProductNames[ $i ],
				'price' => fake()->numberBetween( 10000, 15000 ),
				'weight' => fake()->numberBetween( 1000, 300 ),
				'manufacture_cost' => fake()->numberBetween( 1000, 2000 ),
				'sold' => false,

			] );
		}
		// GoldPrice::insert( [
		// 	[
		// 		'standard' => ' 24 Karat Gold',
		// 		'description' => 'description ',
		// 		'price' => 36000,
		// 	], [
		// 		'standard' => ' 18 Karat Gold',
		// 		'description' => 'description ',
		// 		'price' => 3300,
		// 	], [
		// 		'standard' => ' 14 Karat Gold',
		// 		'description' => 'description ',
		// 		'price' => 3200,
		// 	], [
		// 		'standard' => ' 10 Karat Gold',
		// 		'description' => 'description ',
		// 		'price' => 3000,
		// 	],
		// ] );
		// Shop::create( [ 'name' => 'Shop gold 1' ] );
		// Shop::create( [ 'name' => 'Shop gold 2' ] );
		// Value::create( [ 'tax' => 10 ] );
		// for ( $i = 1; $i <= 50; $i++ ) {
		// 	Category::create( [ 'name' => "category " . fake()->words( 3, true ) ] );
		// 	Producer::create( [ 'name' => "Producer " . fake()->words( 3, true ) ] );
		// 	Creditor::create( [
		// 		'name' => fake()->name(),
		// 		'phone' => fake()->phoneNumber(),
		// 		'address' => fake()->address(),
		// 		'money_balance' => fake()->numberBetween( 1000, 10000 ),
		// 		'gold_balance' => fake()->numberBetween( 1000, 10000 ),
		// 	] );
		// 	$product = Product::create( [
		// 		'category_id' => $i,
		// 		'producer_id' => $i,
		// 		'name' => 'Product  ' . fake()->words( 3, true ),
		// 		"price" => fake()->numberBetween( 1000, 10000 ),
		// 		'standard' => fake()->randomElement( [ " 24 Karat Gold", "18 Karat Gold", "14 Karat Gold", "10 Karat Gold" ] ),
		// 		'weight' => fake()->numberBetween( 10, 100 ),
		// 		'manufacture_cost' => fake()->numberBetween( 100, 500 )
		// 		// 'in_Stock' => ,
		// 		// 'quantity' => 80 + $i,
		// 		// 'tax' => 7 / $i,
		// 	] );
		// 	for ( $k = 1; $k <= 10; $k++ ) {
		// 		$item = Item::create( [
		// 			'product_id' => $i,
		// 			'creditor_id' => $i,
		// 			'shop_id' => fake()->randomElement( [ 1, 2 ] ),
		// 			"invoice_id" => null,
		// 			'code' => "code-product-$i-item-$k",
		// 			'sold' => false,
		// 		] );
		// 		ItemCountHelper::incrementItemCount( $item );
		// 	}
		// }
	}
}
