<?php

namespace App\Providers;

use App\Models\{
    Category,
    Product,
    User,
    Profile,
    Transaction
};
use App\Repositories\{
    CategoryRepository,
    UserRepository,
    ProductRepository,
    ProfileRepository,
    TransactionRepository
};
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository($app->make(User::class));
        });
        $this->app->bind(ProfileRepository::class, function ($app) {
            return new ProfileRepository($app->make(Profile::class));
        });
        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository($app->make(Category::class));
        });
        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository($app->make(Product::class));
        });
        $this->app->bind(TransactionRepository::class, function ($app) {
            return new TransactionRepository($app->make(Transaction::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('money', function ($amount) {
            return "<?php echo 'Rp. ' . number_format($amount, 2, ',', '.'); ?>";
        });
    }
}
