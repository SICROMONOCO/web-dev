#!/usr/bin/env node
/**
 * Convert MicroBlog HTML Report to PDF
 * Uses Puppeteer to generate PDF from HTML
 */

const puppeteer = require("puppeteer");
const fs = require("fs-extra");
const path = require("path");

(async () => {
  try {
    console.log("🚀 Generating PDF report for MicroBlog project...");

    const htmlPath = path.join(__dirname, "REPORT.html");
    
    if (!fs.existsSync(htmlPath)) {
      console.error("❌ REPORT.html not found. Run generateReport.js first.");
      process.exit(1);
    }

    const browser = await puppeteer.launch({
      headless: true,
      args: ["--no-sandbox", "--disable-setuid-sandbox"],
    });

    const page = await browser.newPage();
    
    // Read the HTML content
    const htmlContent = fs.readFileSync(htmlPath, 'utf8');
    
    // Set the content
    await page.setContent(htmlContent, { waitUntil: "networkidle0" });
    
    // Generate PDF
    const pdfPath = path.join(__dirname, "../../reports/TP8_MicroBlog_Report.pdf");
    await page.pdf({
      path: pdfPath,
      format: "A4",
      printBackground: true,
      margin: {
        top: "20px",
        right: "20px",
        bottom: "20px",
        left: "20px",
      },
    });

    await browser.close();

    console.log(`✅ PDF generated successfully: ${pdfPath}`);
  } catch (error) {
    console.error("❌ Error generating PDF:", error);
    process.exit(1);
  }
})();
