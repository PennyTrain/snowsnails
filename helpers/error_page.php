<?php
class ErrorPage
{
    private int $code;
    private string $title;
    private string $message;
    private string $image;
    private string $buttonText;
    private string $buttonLink;

    public function __construct(
        int $code,
        string $title,
        string $message,
        string $image,
        string $buttonText = "Go Home",
        string $buttonLink = "/dab502/assignment/snowsnail/index.php",
    ) {
        $this->code = $code;
        $this->title = $title;
        $this->message = $message;
        $this->image = $image;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
    }

    public function render(): void
    {
        http_response_code($this->code);
        include __DIR__ . "/../header.php";
        ?>
        <div class="user-container error-page">
            <h1 class="heading error-code"><?= htmlspecialchars((string) $this->code) ?></h1>

            <p class="text error-text">
                <?= htmlspecialchars($this->message) ?>
            </p>

            <div class="error-image-wrapper">
                <img
                    src="<?= htmlspecialchars($this->image) ?>"
                    alt="<?= htmlspecialchars($this->title) ?>"
                    class="error-image"
                >
            </div>

            <a href="<?= htmlspecialchars($this->buttonLink) ?>" class="error-button">
                <?= htmlspecialchars($this->buttonText) ?>
            </a>
        </div>
        <?php
        include __DIR__ . "/../footer.php";
    }
}