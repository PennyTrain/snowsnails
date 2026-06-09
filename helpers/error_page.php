<?php
// creates a class called error page
class ErrorPage
{
    // these are the stored values
    private int $code;
    private string $title;
    private string $message;
    private string $image;
    private string $buttonText;
    private string $buttonLink;

    public function __construct(
        // setting the values when the object is created
        int $code,
        string $title,
        string $message,
        string $image,
        string $buttonText = "Go Home",
        string $buttonLink = "../index.php"
    ) {
        // sae the input into the classes 
        $this->code = $code;
        $this->title = $title;
        $this->message = $message;
        $this->image = $image;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
    }
    // the function that shows the page
    public function render(): void
    {
        http_response_code($this->code);
        include "../header.php";
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
        include "../footer.php";
    }
}