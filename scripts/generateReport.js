/**
 * GitHub Auto Report Generator (No PhantomJS)
 * --------------------------------------------
 * - Detects all TP folders (TP1, TP2, ...)
 * - Takes screenshots of *every* .html file in each TP folder
 * - Builds a PDF report using Puppeteer (no markdown-pdf)
 */

const puppeteer = require("puppeteer");
const fs = require("fs-extra");
const path = require("path");

(async () => {
  try {
    console.log("🚀 Starting report generation...");

    const projects = fs
      .readdirSync(".")
      .filter((f) => /^TP\d+/i.test(f) && fs.lstatSync(f).isDirectory());

    if (projects.length === 0) {
      console.log("⚠️ No TP folders found (expected TP1, TP2, ...)");
      process.exit(0);
    }

    const pdfSections = [];

    const browser = await puppeteer.launch({
      headless: true,
      args: ["--no-sandbox", "--disable-setuid-sandbox"],
    });

    for (const dir of projects) {
      const htmlFiles = fs
        .readdirSync(dir)
        .filter((file) => file.endsWith(".html"));

      if (htmlFiles.length === 0) {
        console.log(`⚠️ Skipping ${dir}: no HTML files found.`);
        continue;
      }

      let section = `
<h1>${dir}</h1>
<h1>TP</h1>
<h2>Student Information</h2>
<ul>
  <li><strong>Student(s) Name(s): bilal siki</strong></li>
</ul>

<h2>Project Repository</h2>
<ul>
  <li><strong>GitHub Link:</strong> <code>${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}</code></li>
</ul>

<h2>Project Output</h2>
`;

      for (const file of htmlFiles) {
        const filePath = path.join(dir, file);
        const page = await browser.newPage();
        await page.goto(`file://${process.cwd()}/${filePath}`, {
          waitUntil: "networkidle0",
        });

        const screenshotPath = `${dir}_${file.replace(".html", "")}.png`;
        await page.screenshot({ path: screenshotPath, fullPage: true });
        await page.close();

        console.log(`📸 Captured: ${screenshotPath}`);
        section += `<h3>${file}</h3><img src="${screenshotPath}" style="max-width:100%;border:1px solid #ccc;margin-bottom:10px;">`;
      }

      section += `<h2>Notes</h2><hr>`;
      pdfSections.push(section);
    }

    await browser.close();

    const htmlReport = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Web Dev Report</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    h1 { color: #1e88e5; }
    img { display:block; margin:auto; }
    hr { margin: 40px 0; }
  </style>
</head>
<body>
  ${pdfSections.join("\n\n")}
</body>
</html>
`;

    fs.writeFileSync("report.html", htmlReport);

    console.log("🧾 Generating PDF using Puppeteer...");
    const browser2 = await puppeteer.launch({
      headless: true,
      args: ["--no-sandbox", "--disable-setuid-sandbox"],
    });
    const page = await browser2.newPage();
    await page.setContent(htmlReport, { waitUntil: "networkidle0" });
    await page.pdf({ path: "report.pdf", format: "A4", printBackground: true });
    await browser2.close();

    console.log("✅ PDF report generated successfully: report.pdf");
  } catch (error) {
    console.error("❌ Error generating report:", error);
    process.exit(1);
  }
})();
