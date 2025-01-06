const { Client, LocalAuth, MessageMedia, Buttons } = require("whatsapp-web.js");
const qrcode = require("qrcode-terminal");
const process = require("process");
const puppeteer = require("puppeteer");

// Check if phone number and message are provided as arguments
const [, , phoneNumber, emessage, imagePath] = process.argv;
const message = Buffer.from(emessage, "base64").toString("utf-8");
const phoneNumberArray = phoneNumber.split(","); // Split and trim the phone numbers

if (!phoneNumberArray.length || !message) {
    console.error("Error: Phone number and message are required.");
    process.exit(1);
}

// Use LocalAuth to store session data locally
const client = new Client({
    authStrategy: new LocalAuth({
        clientId: "client-one", // Unique identifier for this session
        dataPath: "D:/PROJECT/LARAVEL/sppd/node_server/.wwebjs_auth", // Explicit path
    }),
    puppeteer: {
        executablePath: puppeteer.executablePath(), // Use puppeteer-core
        headless: true, // Ensure headless is true to run without UI
        args: [
            "--no-sandbox",
            "--disable-setuid-sandbox",
            "--disable-dev-shm-usage",
            "--disable-accelerated-2d-canvas",
            "--no-first-run",
            "--no-zygote",
            "--single-process", // <- this one doesn't work in Windows
            "--disable-gpu",
            "--disable-background-timer-throttling",
            "--disable-backgrounding-occluded-windows",
            "--disable-renderer-backgrounding",
        ], // Extra args for Puppeteer

        timeout: 30000, // Set the timeout for the Puppeteer instance
    },
});

client.on("qr", (qr) => {
    // Generate and display the QR code
    qrcode.generate(qr, { small: true });
    console.log("Scan the QR code above to login");
});

client.on("ready", async () => {
    console.log("WhatsApp client is ready");
    await sendMessage();
});

client.on("authenticated", (session) => {
    // Session is saved automatically using LocalAuth
    console.log("Authenticated successfully", session);
});

client.on("auth_failure", () => {
    console.log("Authentication failed, please scan the QR code again");
});

client.on("message", (message) => {
    console.log(`Received message: ${message.body}`);
});

client.on("error", (err) => {
    console.error("Client Error:", err);
});

process.on("uncaughtException", (err) => {
    console.error("Uncaught Exception:", err);
});

process.on("unhandledRejection", (err) => {
    console.error("Unhandled Rejection:", err);
});

// Load saved session if it exists
client.initialize();

const sendMessage = async () => {
    for (const numbers of phoneNumberArray) {
        const number = numbers.includes("@c.us") ? numbers : `${numbers}@c.us`;
        try {
            if (imagePath) {
                const media = MessageMedia.fromFilePath(imagePath);
                const mediaResponse = await client.sendMessage(number, media, {
                    caption: message,
                    linkPreview: true,
                });
                console.log(
                    `Image sent successfully to ${phoneNumber}`,
                    mediaResponse
                );
            } else {
                const response = await client.sendMessage(number, message);
                console.log("Message sent successfully", response);
            }
        } catch (error) {
            console.error("Failed to send message:", error);
        }
    }

    // Delay the client.destroy() to ensure the message is sent
    setTimeout(async () => {
        await client.destroy(); // Await the destroy call to ensure the client is properly closed
        console.log("Client destroyed, browser closed");
    }, 3000); // Adjust the delay as needed
};
