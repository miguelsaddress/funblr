# Funblr

Welcome to Funblr. Here you can do just one thing: Upload a picture, be it a png, a jpeg or a cool gif file.
And they can be fat, you can upload images up to 20MB each.

And if you like, your image can have a title. Don't know what to add as a title? never mind, because it is optional.

You can also download a CSV or Excel file containing a list of your images and their URL. 
What? you want the images as well? I could expect that, so you can download a zip file containing all the images and the CSV and Excel files.
Just remember it is no magic and it takes its time, so do not reload the page until your download has started.

Set up your own Funblr:
----

Funblr is build using Laravel 5.2 framework in PHP. All you have to do is to clone the repo and run `composer install`.
Once you downloaded all the staff via composer, you will have to set up a few simple things, for that, make a `.env` file copying the `.env.example` file
This is a laravel feature... So it is better if you have a look at its documentation. 

For the `Funblr` app you will need an s3 bucket (where yo can write and so on... remember to define your policies right) in amazon so you need to add at least the following fields:

````
AWS_KEY=your aws key
AWS_SECRET=your aws secret
BUCKET_NAME=the s3 bucket name
S3_FOLDER=a folder in the bucket. For example to distinguish dev mode to prod mode

````

#Contact

You can contact me at `twitter`: [@miguelsaddress](http://twitter.com/miguelsaddress)