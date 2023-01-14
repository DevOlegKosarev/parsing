<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->

<a name="readme-top"></a>

<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Thanks again! Now go create something AMAZING! :D
-->

<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->

[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/DevOlegKosarev/parsing">
    <img src="https://raw.githubusercontent.com/DevOlegKosarev/DevOlegKosarev/main/images/logo.png" alt="project_title" width="80" height="80">
  </a>

  <h3 align="center">Parsing Products Vendors</h3>
  <p align="center">
    Parsing Product From Vendors
    <br />
    <a href="https://github.com/DevOlegKosarev/parsing"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/DevOlegKosarev/parsing">View Demo</a>
    ·
    <a href="https://github.com/DevOlegKosarev/parsing/issues">Report Bug</a>
    ·
    <a href="https://github.com/DevOlegKosarev/parsing/issues">Request Feature</a>
  </p>
</div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->

## About The Project

[![Product Name Screen Shot][product-screenshot]](https://example.com)

Here's a blank template to get started: To avoid retyping too much info. Do a search and replace with your text editor for the following: `DevOlegKosarev`, `parsing`, `twitter_handle`, `linkedin_username`, `email_client`, `email`, `project_title`, `project_description`

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Built With

- [![Bootstrap][bootstrap.com]][bootstrap-url]
- [![JQuery][jquery.com]][jquery-url]

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->

## Getting Started

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

## Demo

Here is a working live demo : <https://demo.foxway.com/>

### Prerequisites

This is an example of how to list things you need to use the software and how to install them.

### Installation

1. Clone the repo

   ```sh
   git clone https://github.com/DevOlegKosarev/parsing.git
   ```

2. Make Vendor files in `vendors\<nameVendor>.php`

   ```php
    require_once AppPatch . "/vendors.php";
    final class FoxWay extends Vendors
    {
        public function __construct(object $config)
        {
            parent::__construct($config);
            # code magic
        }
    }
   ```

3. Make Start Vendor file in Root `<nameVendor>.php`

   ```php
    define('AppPatch', __DIR__);
    require_once  __DIR__ . "/vendors/<nameVendor>.php";

    $config = new stdClass();
    $config->debug = true;
    $config->logger = true;
    $config->basePath = '<nameVendor>';

    $vendorClassName = new <vendorClassName>($config);
    $vendorClassName->allProducts();
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- USAGE EXAMPLES -->

## Usage

Parsing products from your vender and prepare For your Best CMS

_For more examples, please refer to the [Documentation](https://docs.datamarket.lv)_

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- ROADMAP --> 

## Roadmap

- [x] FoxWay
- [ ] CodeIgnaiter

See the [open issues](https://github.com/DevOlegKosarev/parsing/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTRIBUTING -->

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- LICENSE -->

## License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTACT -->

## Contact

Oleg Kosarev - [@twitter_handle](https://twitter.com/twitter_handle) - email@email_client.com

Project Link: [https://github.com/DevOlegKosarev/parsing](https://github.com/DevOlegKosarev/parsing)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[stars-shield]: https://img.shields.io/github/stars/DevOlegKosarev/parsing.svg?style=for-the-badge
[stars-url]: https://github.com/DevOlegKosarev/parsing/stargazers
[issues-shield]: https://img.shields.io/github/issues/DevOlegKosarev/parsing.svg?style=for-the-badge
[issues-url]: https://github.com/DevOlegKosarev/parsing/issues
[license-shield]: https://img.shields.io/github/license/DevOlegKosarev/parsing.svg?style=for-the-badge
[license-url]: https://github.com/DevOlegKosarev/parsing/blob/master/LICENSE.txt
[product-screenshot]: https://raw.githubusercontent.com/DevOlegKosarev/DevOlegKosarev/main/images/screenshot/parsing.png
[bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[bootstrap-url]: https://getbootstrap.com
[jquery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[jquery-url]: https://jquery.com
