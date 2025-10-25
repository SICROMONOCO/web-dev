const puppeteer = require('puppeteer');
const fs = require('fs-extra');
const markdownpdf = require('markdown-pdf');
const path = require('path');

(async () => {
  // Detect all "TP" folders (TP1, TP2, TP3, ...)
  const projects = fs.readdirSync('.').filter(f => /^TP\d+/.test(f));
  const mdContent = [];

  for (const dir of projects) {
    const indexPath = path.join(dir, 'index.html');
    if (!fs.existsSync(indexPath)) continue;

    console.log(`📸 Capturing screenshot for ${dir}...`);

    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();
    await page.goto(`file://${process.cwd()}/${indexPath}`);
    const screenshotPath = `${dir}_main.png`;
    await page.screenshot({ path: screenshotPath, fullPage: true });
    await browser.close();

    mdContent.push(`
# ${dir}

## Student Information
- **Student(s) Name(s):**

## Project Repository
- **GitHub Link:** \`${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}\`

## Project Output
### Main Page(s) Screenshot
![](${screenshotPath})

## Notes
---
`);
  }

  if (mdContent.length === 0) {
    console.log('⚠️ No TP folders found with index.html files.');
    process.exit(0);
  }

  const markdown = mdContent.join('\n\n');
  fs.writeFileSync('report.md', markdown);

  console.log('🧾 Converting Markdown to PDF...');
  markdownpdf().from('report.md').to('report.pdf', () => {
    console.log('✅ PDF report generated: report.pdf');
  });
})();
