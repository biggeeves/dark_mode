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


User Names:  Specify specific users that should see the Dark Skin Mode. 
If left blank all admins and only admins will use the custom colors.

Background Primary Color: The main background color.

Background Secondary and Tertiary colors are more than just background colors.  They are also the colors of hovers, roll overs, pop ups, modals, button backgrounds and a touch of other things.

Background Brightness: More than one background color is needed to create effects like buttons.
Options are: 
a. Same Color as Primary Background
b. Lighter
c. Darker
d. Specify

Choosing Lighter or Dark will open a field where the percent brightness change is set
Set this from 0% to 100%.  For example 10% darker will have buttons that are 10% darker than the background

Choose "Specify" to set your own secondary and tertiary colors which are mainly used for buttons and pop ups

Primary Text Color.  Specify the color that most text should be in REDCap.

Link Color: Specify the color link will be.


These are color combos I like.  To each their own!
Primary Background: #27221f
Primary Text: #C0C0C0
Link: 

An easy on the eye, grey scale color
Primary Background Color: #222
Other backgrounds: Lighter by 15%
Primary text color: #ddd
Secondary text color: #98AFC7
Link color: #f0f0f0

In sections like the Help & FAQ the system settings will be applied.  This may be a bit jarring if a project has custom colors and they view the Help and FAQ section to see the system colors.

REDCap codes many important things in RED and sometimes green.  Some items like required, identifier, deletes and warning were left as they are coded in REDCap.  Please keep that in mind while choosing a color schema.  