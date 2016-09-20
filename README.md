# RWD2

Last modified: 8/22/2016 10:52 AM

RWD2, aka, the Standard Model, is my newest implementation of the master-template approach. This implementation contains a single one-region template used in all pages across multiple sites of the same design. My cascade-admin site is driven by this newest implementation.

All the code I can post used in the implementation of RWD2 at Upstate can be found here. What is not included here are:

1. Velocity formats, which are posted under https://github.com/wingmingchan/velocity
2. PHP code used to generate CSS

The core without (much) Upstate business logic and data

1.  _common_assets_barebone.csse

What are used in production at Upstate:

1. _common_assets.csse
2. _rwd_seed.csse

What are used to generate the Hannon Hill site:

1. _common_assets_wing.csse
2. hannonhill-wing.csse

Tutorial recordings: https://www.youtube.com/playlist?list=PLiPcpR6GRx5dN3Z5-tAAMLgFX59Njkv6f