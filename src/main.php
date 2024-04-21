<?php

namespace FeedbackPlugin;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\utils\TextFormat as TF;

class FeedbackPlugin extends PluginBase {

    public function onEnable() {
        $this->getLogger()->info("FeedbackPlugin telah diaktifkan");
    }

    public function onDisable() {
        $this->getLogger()->info("FeedbackPlugin telah dinonaktifkan");
    }

    public function openFeedbackForm(Player $player) {
        $form = new CustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return;
            }
            $feedback = $data[0];
            $this->saveFeedbackToFile($player, $feedback);
            $player->sendMessage(TF::GREEN . "Terima kasih atas masukan Anda!");
        });
        $form->setTitle("Kotak Saran");
        $form->addInput("Masukkan masukan Anda di sini:");
        $player->sendForm($form);
    }

    public function openFeedbackMenu(Player $player) {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $this->openFeedbackForm($player);
                    break;
            }
        });
        $form->setTitle("Kotak Saran");
        $form->setContent("Pilih salah satu opsi untuk memberikan masukan:");
        $form->addButton("Berikan Masukan");
        $player->sendForm($form);
    }

    private function saveFeedbackToFile(Player $player, string $feedback) {
        $filename = $this->getDataFolder() . "feedback.txt";
        $handle = fopen($filename, "a");
        if ($handle !== false) {
            fwrite($handle, "[" . date("Y-m-d H:i:s") . "] " . $player->getName() . ": " . $feedback . "\n");
            fclose($handle);
        } else {
            $this->getLogger()->warning("Tidak bisa menyimpan masukan ke file.");
        }
    }
}
