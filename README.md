   LSB steganography with PHP (v1.0)
=======================================

![output](https://github.com/user-attachments/assets/70dc03e4-c86c-4494-bba6-c3c359ad4b5e)     ![example](https://github.com/user-attachments/assets/aa763dcd-eff2-48c9-be65-cca47cbe1a8d)


Can you spot the difference between these two images?  
One of them hides a secret message using Least Significant Bit (LSB) steganography.

LSB steganography is a method that hides a few bits of information inside each pixel. More precisely, each pixel color is composed of three color channels: red (R), green (G) and blue (B). Each single color is coded on 8 bits. The value of the eighth bit, also called the least significant bit (LSB), is replaced with one bit from the secret message. It this then possible to hide 3 bits of secret information inside each pixel.

This directory contains two PHP scripts. The first one `encode.php` hides a secret message inside a PNG image. The second script `decode.php` scans the output image generated from the first script and retrieves the secret message. As a bonus step, it also checks whether the retrieved text is equal to the original one by using a quick hash function. These scripts have been written for learning purposes only.


Usage
-----

First install PHP 7.4.26 or above. Then, call the first script `encode.php` in the following way:

```
http://localhost/encode.php?file=secret
```

It will generate an output image `data/output.png`. Only then, call the second script `decode.php`:
```
http://localhost/decode.php
```

You should see « *The original secret message has been successfully retrieved!* » followed by the secret message.

Future work
-----------

A few ideas to improve this project:
- Add support for JPEG, BMP and GIF images (easy).
- Automatically compress the secret message before hiding it.
- Make the script working with general binary files.
- Find a way to optimize the script's execution speed.

License
-------

This work is shared under the [MIT license](LICENSE).  
The original rabbit image is shared under CC0 Public Domain licence and has been taken [from here](https://www.publicdomainpictures.net/en/view-image.php?image=654799&picture=stargazing-bunny-rabbit-art-print).
