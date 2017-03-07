# RWD2

_core.csse last modified: 3/7/2017 8:00 AM

RWD2, aka, the Standard Model, is my newest design and implementation of the master-template approach. This implementation contains a single one-region template (template_rwd_one_region.xml) used in all pages across multiple sites of the multiple designs. In fact, in the implementation of the newest master site (https://github.com/wingmingchan/velocity/blob/master/multiple-design/_master_site.csse), there is only one line (<code>&lt;system-region name="DEFAULT"/&gt;</code>) in the template. I have also introduced the concept of setup formats. They can be used to replace setup blocks and offer much more flexibility.

The Standard Model is also compatible with multiple-region templates (see template_rwd.xml, for example). All Upstate responsive sites are driven by the Standard Model.

All the code I can post used in the implementation of the Standard Model can be found here. What are not included here:

1. Velocity formats, which are posted under https://github.com/wingmingchan/velocity
2. PHP code used to generate CSS

The core containing only library code

1.  _core.csse

What are used in production at Upstate (last modified: 1/24/2017):

1. _common_assets.csse
2. _rwd_seed.csse

What are used to generate the Hannon Hill site (last modified: 1/24/2017):

1. _common_assets_wing.csse
2. hannonhill-wing.csse

<h3>Documentation</h3>
<ul>
<li><a href="http://www.upstate.edu/cascade-admin/standard-model/index.php">The Standard Model</a></li>
</ul>

<h3>Tutorial Recordings</h3>
<ul>
<li><a href="https://www.youtube.com/playlist?list=PL5FL7lAbKiG-AYX35qK8y0FN7RgJl9ISD">Velocity and More</a> recordings</li>
<li><a href="https://www.youtube.com/playlist?list=PLiPcpR6GRx5dN3Z5-tAAMLgFX59Njkv6f">One Template, One Region, and Lots of Velocity Tricks</a></li>
</ul>