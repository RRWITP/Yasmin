# Installation 

For Yasmin, we need PHP 7.1 (or higher) and composer in order to work. 

## PHP 

Please refer to the specific guide for your platform. Please also not that Yasmin does **not** run on 
any webservers and **only** runs in the Command line interface. Thus installing any webserver or webserver-specific package is not 
necessary or ideal. 

## Composer 

Please refer to [the official getting stated page](https://getcomposer.org/doc/00-intro.md).

## Yasmin

For the rest of the installation we rely on composer. First we need to create a new project using
`composer init`. Composer will take us trough the steps. As soon as it's done, we use `composer require charlottedumios/yasmin` to install Yasmin and its dependencies. 
It might take a whike. 

## Discord 

Meanwhile you can create the application for your bot. For that you need to visit the [Discord developers page](https://discord.com/developers/applications),
if you aren't logged in into your Discord account yet in your browser, login into your account (you get sked to do that anyway)

There are these steps to create an application. 

- Open the Discord Developers page login if neccessary. 
- Click on the big add button "New app". 
- Fill the form, only name is required. 
- Click on the "create App" button once you're done

Once the app got created, we get redirect to the Application details page; 

Now, this is an application for Oauth, we can't use this for our bot yet. The last step is to click the "Create Bot User" button below. 
One you have a bot user, you will the information box on the page. After that just celebrate because you're the proud owner of a new 
Discord bot. 

## Oauth token 

**This part is important; so play close attention.**

A Oauth token is like a password, it act as a key to login into your bot account. Do not share tokens with anyone, 
dont' upload it anywhere, **neither accidentally nor purposefully**. You, as the owner, is responsible and liable for anything that open on the account. 

if you click on `click to reveal`, you will get shown the token. The token gets each time you click on it generated. 
but don't fear - they are valid until you cliock `regenerate the token`. When you get asked to put the token into somewhere in your script, then it's what you see when you click on `click to reveal`.
