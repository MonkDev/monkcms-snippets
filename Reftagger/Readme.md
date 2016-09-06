# Instructions for using Reftagger on e360 sites

## Basic Instructions

- _require.js_ Follow the steps located at [https://github.com/MonkDev/monkcms-snippets/blob/master/Reftagger/reftagger-and-require.js](https://github.com/MonkDev/monkcms-snippets/blob/master/Reftagger/reftagger-and-require.js) to update the javascript related files.
- _regular js_ Follow the steps located at [https://github.com/MonkDev/monkcms-snippets/blob/master/Reftagger/reftagger-regular.js](https://github.com/MonkDev/monkcms-snippets/blob/master/Reftagger/reftagger-regular.js) to update the javascript related files.

1. Change all instances of `__passage__` and `__passagelink__` in the **sermon layouts** as well as **ekk_sermonpage.php** with `__passagebook__ __passageverse__`.
2. Go to *CMS - Site Config > Account* and uncheck the box next to **BIBLE CLOUD _Integrate with Biblecloud.com_**
3. Clear the site cache
4. Test

http://reftagger.com/
http://requirejs.org

---
