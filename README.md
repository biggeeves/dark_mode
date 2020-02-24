# Dark Mode

Dark Mode is an External Module that allows you to change the colors of REDCap.  

REDCap allows survey's to be customized, but the main logged in experience has yet to be customized.  This E.M. changes that.

Three background colors can be defined.  Two text colors can be defined.  One link color can be defined.

This E.M. also removes many of REDCap's style's in favor of a simpler presentation. 

Projects may choose their own colors.

System administrators can disallow custom project colors.

Not everybody will love this.  For those that do, add specify their user names, separated by commas.  Only they will see the customization.

It works by defining simple CSS overrides.

More Information:


<strong>User Names</strong>:  Specify specific users that should see the Dark Skin Mode. 
If left blank all admins and only admins will use the custom colors.

<strong>Primary Background Color</strong>: The background color used on the right side, the non-navigation area.    

<strong>Secondary Background Color:</strong> The background color of the left side navigation area. Buttons are also set to this color.  The background color for all fields. 

<strong>Tertiary Background Color:</strong>Rarely used.  Button borders for navigation links.  

Note: Primary, Secondary and Tertiary look best when they are slight shade variants of the same color or very complimentary colors. The primary and secondary background colors make pop ups stand out and fields stand out as well.   

<strong>Background Brightness</strong>: More than one background color is needed to create effects like buttons.
Options are: 
<ul>
<li>a. Same Color as Primary Background</li>
<li>b. Lighter</li>
<li>c. Darker</li>
<li>d. Specify</li>
</ul>

Choosing Lighter or Dark will open a field where the percent brightness change between backgrounds is set.
The range is 0% to 100% the color of the primary background color.  For example, 10% darker will have buttons that are 10% darker than the background.

Choose "Specify" to set your own secondary and tertiary colors which are mainly used for buttons and pop ups

<strong>Primary Text Color.</strong>  Specify the color that most text should be in REDCap.

<strong>Secondary Text Color.</strong> A color that will stand out more for items like section headers.

<strong>Tertiary Text Color.</strong>  A color that will stand out less; for items like side notes and foot notes.

<strong>Link Color</strong>: Specify the link color.  Most, links will be this color. The text inside button links will be this color.


These are color combos I like.  If you have a color combo that is amazing, let me know and I'll include it here.

If your in a chocolate sort of mood:
Primary Background: #27221f
Background Brightness: Lighter by 10%
Primary Text: #C0C0C0
Secondary Text: #f8f8f8
Tertiary Text: #C0C0C0
Link: #ffffff

An easy on the eye, grey scale color
Primary Background Color: #222222
Other backgrounds: Lighter by 15%
Primary text color: #ddd
Secondary text color: #98AFC7
Link color: #f0f0f0

Very Dark Background, blue links and white text. 
Primary Background Color: #111111
Other backgrounds: Lighter by 15%
Primary text color: #FFF
Secondary text color: #98AFC7
Link color: #AED6F1

Feeling blue & pink pastel?
Primary Background: #d0efff
Secondary Background: #ffd0d7
Tertiary Background: #ff9dac

Primary Text: #111
Secondary Text: #111
Tertiary Text: #111
Link: #0c0002




In sections like the Help & FAQ the system settings will be applied.  This may be a bit jarring if a project has custom colors and they view the Help and FAQ section to see the system colors.

REDCap codes many important things in RED and sometimes green.  Some items like required, identifier, deletes and warning were left as they are coded in REDCap.  Please keep that in mind while choosing a color schema.  