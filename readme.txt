=== Woocommerce Pay.nl Payment Methods ===
Contributors: andypay
Donate link: https://www.pay.nl/webshops/plugin-woocommerce
Link: http://www.pay.nl
Tags: paynl, paymentmethods, woocommerce, ideal, paypal, creditcard, mybank, sofortbanking, afterpay, mistercash, bancontact, paysafecard, clickandbuy, giropay, incasso, betaalmethoden
Requires at least: 3.0.1
Stable tag: 2.3.4
Tested up to: 4.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds the pay.nl payment methods to your woocommerce (2.1 and higher) installation.

== Description ==

*By installing this plugin you'll be able to integrate the payment methods of Pay.nl to your WooCommerce Webshop. This will only take a few minutes. If the plugin is successfully installed your customers will then be able to checkout their orders through the payment options such as iDeal, PayPal, Creditcard and so on.*

Payment Service Provider Pay.nl offers online payment options for webshops and websites. Pay.nl has different payment option packages available. These packages range from the smallest one (XS) to the largest one (XL). Not sure which package is suitable for you? Start with the try-out package called Pioneer XS! With the Pioneer XS you will be able to use all payment options  except for the options credit card and direct debit. Just fill in the registration form and you'll receive a personal account by email. With this account you have direct access to the Pay.nl Administration Panel where you can add your website(s) and install the payment options that are essential for your website. 

With Pay.nl you can add the following payment options to your website:

* iDeal
* Direct debit
* Credit card (Visa / MasterCard)
* Bancontact / Mister Cash
* Giropay
* Sofortbanking
* Banktransfer EU
* Afterpay
* PayPal
* ClickandBuy
* Mybank
* Gezondheidsbon
* Fashioncheque
* Podiumcadeaukaart

For the registration form, visit the registration page (in Dutch): [www.pay.nl/registreren](http://www.pay.nl/registreren)
For more information about the payment rates go to [www.pay.nl/tarieven](htp://www.pay.nl/tarieven)
For more information about the payment solution packages, just visit [www.pay.nl/pakketten](http://www.pay.nl/pakketten)

You can download the manual for this plugin (in Dutch) [here](http://www.pay.nl/plugin/woocommerce/pdf)

For any further questions please send an email to support@pay.nl

== Installation ==

This section describes in short how to install the plugin and get it working.
If you need more help, you can download the manual [here](http://www.pay.nl/plugin/woocommerce/pdf) or send an email to [support@pay.nl](mailto:support@pay.nl)

1. Install the plugin via Plugins -> new plugin
2. Activate the plugin through the 'Plugins' menu in WordPress, the name of this plugin is: Woocommerce Pay.nl Payment Methods
3. Under Woocommerce -> Settings -> Payment, configure the apitoken and serviceid and activate the desired payment methods.
4. You can now accept payments using pay.nl


== Frequently Asked Questions ==

= How can i get an account for pay.nl? =

You can register [here](http://pay.nl/registreren) (dutch and belgian companies only)

= Is there a manual available for this plugin? =

Yes there is!
You can download it [here](https://www.pay.nl/plugin/woocommerce/pdf)

= What payment methods are available through this plugin?  =

At the moment the plugin supports the following payment methods:

* Afterpay
* Cartebleue
* Click and buy
* Giropay
* iDEAL
* Incasso
* Maestro
* Minitix sms
* Mistercash/Bancontact
* Manual transfer
* Mybank
* Paypal
* Paysafecard
* Postepay
* Visa/Mastercard
* Gezondheidsbon
* Fashioncheque
* Podiumcadeaukaart

= What does it cost? =

The easiest way to get started is bij using a free pioneer account.
With this account there are no monthly fees, you'll only pay transaction costs for the transactions you make.
Check the pioneer tarriffs [here](http://pay.nl/tarieven-pioneer)

If you want to be able to use creditcards, you'll need to have at least a professional account.
For more information check: [pakketten](http://pay.nl/pakketten)

Paid accounts have better tarriffs! see: [tariffs](http://pay.nl/tarieven)

== Screenshots ==

1. The added settings in woocommerce -> settings -> payments
2. The ideal configuration page
3. The woocommerce checkout page with the pay.nl payment methods
4. The iDEAL payment screen (Rabobank)

== Changelog ==
= 2.3.4 = 
* Updated the way the ip address is fetched, in case a loadbalancer or proxy is used
= 2.3.3 = 
* Added instructions to that can be shown on the thank you page
= 2.3.2 = 
* Fixed the amounts being sent when using discount codes
= 2.3.1 = 
* Updated the translations
* Tested with wordpress 4.2
= 2.3 =
* Added new paymenbtmethods (Gezondheidsbon, Fashioncheque and podiumkadokaart)
* Added a notice, in case CURL is not installed
* Payment methods, that are not enabled in pay.nl cannot be activated in woocommerce
* Vat was not sent to pay.nl correctly in the product lines
= 2.2.9 =
* Removed cancel from the plugin to fix bugs refilling the cart

= 2.2.8 =
* Added refund support

= 2.2.7 =
* Removed anonymous function to support php < 5.3

= 2.2.6 =
* Changed the behaviour of activating/deactivating the plugin
* Added admin notice when woocommerce is not loaded

= 2.2.5 =
* Added some assets

= 2.2.4 =
* Added payment method 'Click and buy'

= 2.2.3 =
* Added new payment method 'Mybank'

= 2.2.2 =
* Fixed a bug where the plugin would not detect woocommerce when using sitewide plugins

= 2.2.1 =
* Compatible with wordpress 2.2



