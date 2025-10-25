/**
 * GitHub Auto Report Generator
 * ----------------------------
 * Scans TP folders (TP1, TP2, …), takes screenshots of index.html pages,
 * and generates a PDF report with screenshots + metadata.
 */

const puppeteer = require('puppeteer');
const fs = require('fs-extra');
const markdownpdf = require('markdown-pdf');
const path = require('path');

(async () => {
  try {
    console.log("🚀 Starting report generation...");

    // Find all folders starting with "TP"
    const projects = fs.readdirSync('.').filter(f => /^TP\d+/.test(f));
    if (projects.length === 0) {
      console.log("⚠️ No TP folders found (expected names like TP1, TP2...).");
      process.exit(0);
    }

    const mdContent = [];

    for (const dir of projects) {
      const indexPath = path.join(dir, 'index.html');
      if (!fs.existsSync(indexPath)) {
        console.log(`⚠️ Skipping ${dir}: no index.html found.`);
        continue;
      }

      console.log(`📸 Capturing screenshot for ${dir}...`);

      // Launch headless browser
      const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
      });
      const page = await browser.newPage();

      // Load the local index.html
      await page.goto(`file://${process.cwd()}/${indexPath}`, { waitUntil: 'networkidle0' });

      // Capture screenshot
      const screenshotPath = `${dir}_main.png`;
      await page.screenshot({ path: screenshotPath, fullPage: true });
      await browser.close();

      // Append markdown section
      mdContent.push(`
# ${dir}

## Student Information
- **Student(s) Name(s): BILAL SIKI**

## Project Repository
- **GitHub Link:** \`${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}\`

## Project Output
### Main Page(s) Screenshot
![](${screenshotPath})

## Notes
---
`);
    }

    // Write Markdown file
    const markdown = mdContent.join('\n\n');
    fs.writeFileSync('report.md', markdown);

    console.log("🧾 Converting Markdown to PDF...");
    markdownpdf().from('report.md').to('report.pdf', () => {
      console.log("✅ Report generated successfully: report.pdf");
    });

  } catch (error) {
    console.error("❌ Error generating report:", error);
    process.exit(1);
  }
})();
