?php /* #?ini charset="utf-8"?

[Event]
# Modify the final HTML processing it through the tidy php software package.
# Cleaning the output html using tidy. This results in clean markup to the end user.
Listeners[]=response/preoutput@BcMarkupTidyOutputFilter::outputFilter

*/
