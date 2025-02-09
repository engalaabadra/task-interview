<?php
use Carbon\Carbon;

function supportedLanguages(){
   return config('app.supported_languages'); // Fetch dynamic languages
}

function systemCurrency(){
   return 'SAR';
}
function countryCurrency(){
   return  geoip(request()->ip())->currency;
}

//for filters
function lang(){
   if(isset(getallheaders()['lang'])) return getallheaders()['lang']  ? getallheaders()['lang'] : config('app.locale');
   else return config('app.locale');
}
function page(){
   return request()->input('page');
}
function query(){
   return request()->input('query');
}
function clientId(){
   return request()->input('client_id');
}

function active(){
   return request()->input('active');
}
function status(){
   return request()->input('status');
}


function message(){
   return request()->input('message');
}

//for filter 
function rate(){
   return request()->input('rate');
}

function fav(){
   return request()->input('fav');
}


function type(){
   return request()->input('type');
}

function login_type(){
   return request()->input('login_type');
}

function randomLink(){
   return request()->input('link');
}

function getTokenPayment($paymentMethod){
   if($paymentMethod == 'moyasar') $token=base64_encode(config("services.moyasar.key_live"));
   if($paymentMethod == 'tap') $token=base64_encode(config("services.tap.key_live"));
   return $token;
}

function isSoftDeletes($model){
   return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model));
}

function currentTime(){
   $currentDateTime = Carbon::now();
   $currentTime = $currentDateTime->toTimeString();
   return $currentTime;
}
function currentDate(){
   $currentDateTime = Carbon::now();
   $currentDate = $currentDateTime->toDateString();
   return $currentDate;
}

function modelName($model){
   return strtolower(class_basename($model)) . 's';
}

function getModelClass($modelName){
   $modelClass = 'App\\Models\\'. ucfirst($modelName);
   //check if exist this model or not
   return class_exists($modelClass) ? $modelClass : null;
}

function total(){
   return request()->get('total', 10);
}
/**
 * Generate a code based on the application environment.
 *
 * @return string
 */
function getCode(): string
{
    return env('APP_ENV') === 'production' ? mt_rand(1000, 9999) : '0000';
}


function filePath($url){
   return 'public/' . ltrim($url, '/storage/');
}

function urlFlag($code){
   return 'https://ipdata.co/flags/'.$code.'.png';
}