# Dark Mode

<p>Dark Mode is an External Module that allows you to change the colors used by REDCap.</p>  

<p>Three background colors can be defined.  Three text colors can be defined.  One link color can be defined.</p>

<p>This E.M. also removes many of REDCap's style's in favor of a simpler presentation.</p> 

<p>Projects may choose their own colors.</p>

<p>System administrators can disallow custom project colors.</p>

<p>Not everybody will love this. For those that do, specify their user names, separated by commas.  Only they will see the customization.</p>

<p>It works by defining simple CSS overrides.</p>

<h3>Settings</h3>

<p><strong>User Names</strong>:  Specify specific users that should see the Dark Skin Mode.<br> 
If left blank all admins and only admins will use the custom colors.</p>

<p><strong>Primary Background Color</strong>: The background color used on the right side, the non-navigation area.</p>    

<p><strong>Secondary Background Color:</strong> The background color of the left side navigation area. Buttons are also set to this color.  Fields will use a combination of primary and secondary background depending on if they are a modal or not.</p> 

<p><strong>Tertiary Background Color:</strong>Rarely used.  Button borders for navigation links.</p>  

Note: Primary, Secondary and Tertiary look best when they are slight shade variants of the same color or very complimentary colors. The primary and secondary background colors make pop ups stand out and fields stand out as well.   

<strong>Background Brightness</strong>: More than one background color is needed to create effects like buttons and modals.
Options are: 
<ul>
<li>Same color as primary background</li>
<li>Lighter</li>
<li>Darker</li>
<li>Specify</li>
</ul>

<p>Choosing "Lighter" or "Darker" will open a field where the percent brightness change between backgrounds is set.<br>
The range is 0% to 100% the color of the primary background color.  For example, 10% darker will have buttons that are 10% darker than the background.
</p>
<p>Choose "Specify" to set your own secondary and tertiary colors which are mainly used for buttons and pop ups.</p>

<p><strong>Primary Text Color.</strong>  Specify the color that most text should be in REDCap.</p>

<p><strong>Secondary Text Color.</strong> A color that will stand out <em>more</em> for items like section headers.</p>

<p><strong>Tertiary Text Color.</strong>  A color that will stand out <em>less</em> for items like side notes and foot notes.</p>

<p><strong>Link Color</strong>: Specify the link color.  Most, links will be this color. The text inside button links will be this color.</p>

<h4>What are some basic color combos?</h4>

<h5>If you're in a chocolate sort of mood:</h5>
<ul>
<li>Primary Background: #27221f</li>
<li>Background Brightness: Lighter by 10%</li>
<li>Primary Text: #C0C0C0</li>
<li>Secondary Text: #f8f8f8</li>
<li>Tertiary Text: #C0C0C0</li>
<li>Link: #ffffff</li>
</ul>

<h5>An easy on the eye, grey scale color</h5>
<ul>
<li>Primary Background Color: #222222</li>
<li>Other backgrounds: Lighter by 15%</li>
<li>Primary text color: #ddd</li>
<li>Secondary text color: #98AFC7</li>
<li>Link color: #f0f0f0</li>
</ul>

<h5>Very Dark Background, blue links and white text.</h5> 
<ul>
<li>Primary Background Color: #111111</li>
<li>Other backgrounds: Lighter by 15%</li>
<li>Primary text color: #FFF</li>
<li>Secondary text color: #98AFC7</li>
<li>Link color: #AED6F1</li>
</ul>

<h5>Feeling blue & pink pastel?</h5>
<ul>
<li>Primary Background: #d0efff</li>
<li>Secondary Background: #ffd0d7</li>
<li>Tertiary Background: #ff9dac</li>
<li>Primary Text: #111</li>
<li>Secondary Text: #111</li>
<li>Tertiary Text: #111</li>
<li>Link: #0c0002</li>
</ul>

<p>If you come up with an amazing color combo let me know!</p>
<p>In sections like the Help & FAQ the system settings will be applied.  This may be a bit jarring if a project has custom colors and they view the Help and FAQ section to see the system colors.</p>
<p>REDCap uses specific colors for many important things.  Sometimes red, green and blue are used in REDCap and it was the author's choice to leave those colors and their meaning in place, unchanged by this E.M.</p>  