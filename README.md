# Rickroll Generator for YOURLS

A big thank you for the inspiration from [BramvdnHeuvel](https://github.com/BramvdnHeuvel) and his [RickRoll-Generator](https://github.com/BramvdnHeuvel/Rickroll-Generator)!

Create Rickroll links with custom preview metadata in YOURLS.

## Description

The **Rickroll Generator** is a plugin for [YOURLS](https://yourls.org/), a self-hosted URL shortening service. With this plugin, you can create special Rickroll links that display a custom preview when shared on platforms like Discord, Facebook, or Twitter. The preview shows a title, description, and image you specify to entice users to click, redirecting them to the classic Rickroll video.

## Features

- **Custom Preview Metadata**: Set a unique title, description, and image for the link preview.
- **Seamless Integration**: Works with your existing YOURLS setup.
- **Easy to Use**: Simple interface for creating Rickroll links.
- **Quick Redirection**: The link redirects to the target URL after a brief delay, allowing the preview to appear on social platforms.

## Installation

1. **Download the Plugin**:

   - Download the plugin from the [GitHub repository](https://github.com/bnfone/yourls-rickroll-generator) or clone it.

2. **Upload to YOURLS Plugins Directory**:

   - Upload the `yourls-rickroll-generator` folder to the `user/plugins` directory in your YOURLS installation.

3. **Activate the Plugin**:

   - Log in to your YOURLS admin area.
   - Go to the "Manage Plugins" page.
   - Enable the "Rickroll Generator" plugin.

## Usage

1. **Access Rickroll Generator**:

   - In the YOURLS admin area, click on "Rickroll Generator" in the menu.

2. **Create a Rickroll Link**:

   - **Target URL**: Enter the URL to redirect to (e.g., the Rickroll video on YouTube).
   - **Custom Keyword (optional)**: If you want a specific keyword for your short URL, enter it here.
   - **Preview Title**: Enter the title to display in the link preview.
   - **Preview Description**: Enter the description for the link preview.
   - **Preview Image URL**: Enter the URL of the image to show in the preview.

3. **Generate the Link**:

   - Click the "Create Link" button.
   - The plugin will create a new short URL with the specified preview metadata.

4. **Share the Link**:

   - Share the generated short URL on social media or in messaging apps.
   - The custom preview will display, enticing users to click.

## How It Works

- **Storing Metadata**: The plugin saves the custom preview metadata (title, description, image) as a JSON-encoded string in the `title` field of the YOURLS database.

- **Displaying the Preview**: When the short URL is accessed, the plugin intercepts the redirection process and shows a custom preview page containing Open Graph meta tags with your specified metadata.

- **Automatic Redirection**: After a brief delay (default 1.5 seconds), the page automatically redirects the user to the target URL using JavaScript.

- **Social Platform Compatibility**: By setting the HTTP status to 200 and providing Open Graph meta tags, social media platforms can display the custom preview when the link is shared.

## Configuration

- **Redirection Delay**:

  - The redirection delay is set to 1.5 seconds by default.
  - You can adjust the delay by modifying the value in the JavaScript `setTimeout` function in the `rickroll_show_preview` function in the plugin code.

  ```javascript
  setTimeout(function () {
      window.location.href = "<?php echo htmlspecialchars($link['url']); ?>";
  }, 1500); // Adjust the delay in milliseconds
  ```

## Requirements

- **YOURLS**: This plugin requires YOURLS version 1.7 or higher.

## Troubleshooting

- **Issues Activating the Plugin**:

  - If you encounter errors activating the plugin, ensure your YOURLS installation meets the version requirements.
  - Check for conflicts with other plugins that might affect URL redirection or metadata handling.

- **Preview Not Displaying on Social Platforms**:

  - Some platforms cache preview data. Use their debugging tools (e.g., Facebook Sharing Debugger) to refresh the cache.
  - Ensure that the redirection delay is sufficient for the platform to read the metadata (1–2 seconds is recommended).

## Contributing

Contributions are welcome! If you find a bug or have a feature request, please open an issue on the [GitHub repository](https://github.com/bnfone/yourls-rickroll-generator).

## Acknowledgments

- Thanks to the YOURLS community for providing an extendable platform for URL shortening.
- This plugin was inspired by the desire to bring a bit of fun and surprise to shared links.
- [BramvdnHeuvel](https://github.com/BramvdnHeuvel)'s [RickRoll-Generator](https://github.com/BramvdnHeuvel/Rickroll-Generator) and his inspiring [YouTube Video](https://www.youtube.com/watch?v=HyNGb8T7LvM)!

---

**Enjoy Rickrolling!**