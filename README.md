# Dark Mode External Module

The Dark Mode External Module allows users to customize the color scheme used by REDCap, enhancing readability and providing a personalized user experience. It works by overriding REDCap’s default styles with a simplified, user specified color palette.

### Features

- Define up to three background colors and three text colors.
- Specify a custom link color.
- Projects can set their own color schemes.
- System administrators can restrict custom project colors.
- Only specific users, as defined by their usernames, will see the customizations.
- Simple CSS overrides are used to implement the color changes.

### How It Works

This module applies custom CSS to modify REDCap’s appearance. It reduces many of REDCap’s default styles, focusing on a cleaner, minimalistic presentation.

### Customization Options

1. Usernames
   - Specify the usernames of individuals who should see Dark Mode. If left blank, only admins will have the dark theme applied by default.
2. Primary Background Color
   - The background color applied to the right side of the screen (the non-navigation area).
3. Secondary Background Color
   - The background color applied to the left side navigation and buttons. Certain fields use a mix of primary and secondary background colors, depending on their context (e.g., modals).
4. Tertiary Background Color
   - This color is rarely used and is mainly applied to button borders and navigation link outlines.
   - Note: For the best visual experience, the primary, secondary, and tertiary background colors should either be different shades of the same color or complementary colors. Proper contrast between these colors helps elements like buttons and fields stand out.
5. Background Brightness
   - Adjust the brightness of additional background elements like buttons and modals relative to the primary background. Available options include:
     - Same as the primary background
     - Lighter
     - Darker
     - Custom
     - If “Lighter” or “Darker” is selected, you can specify the percentage change in brightness (0% to 100%) relative to the primary background color.
6. Text Colors
   - Primary Text Color: The default text color for most content.
   - Secondary Text Color: A bolder color for prominent items like section headers.
   - Tertiary Text Color: A subtler color for less prominent text such as side notes or footnotes.
7.	Link Color
   - Define the color for links, including those inside buttons.

### Example Color Schemes

Chocolate-inspired Palette
- Primary Background: #27221f
- Background Brightness: 10% lighter
- Primary Text: #C0C0C0
- Secondary Text: #f8f8f8
- Tertiary Text: #C0C0C0
- Link Color: #ffffff

Easy-on-the-Eyes Grayscale
- Primary Background: #222222
- Other Backgrounds: 15% lighter
- Primary Text: #ddd
- Secondary Text: #98AFC7
- Link Color: #f0f0f0

Dark Background with Blue Links and White Text
- Primary Background: #111111
- Other Backgrounds: 15% lighter
- Primary Text: #FFF
- Secondary Text: #98AFC7
- Link Color: #AED6F1

Pastel Blue and Pink
- Primary Background: #d0efff
- Secondary Background: #ffd0d7
- Tertiary Background: #ff9dac
- Primary Text: #111
- Secondary Text: #111
- Tertiary Text: #111
- Link Color: #0c0002

Feel free to experiment and find the perfect combination that works for you! If you discover an amazing setup, share it with us.

### Notes
- To disable custom styles and see the default REDCap theme, append ?cssdisabled=1 to the URL.
- When accessing sections like “Help & FAQ,” system-level settings will apply, which might cause a temporary switch in color schemes.
- REDCap uses specific colors (such as red, green, and blue) for certain important actions and statuses. These colors remain unchanged to maintain consistency and clarity.
- This module has not kept up to date with additions to REDCap.
- PULL REQUEST ARE GREATLY APPRECIATED!
