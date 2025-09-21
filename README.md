# 🔥 Anti-Interrupt PvP (PocketMine-MP Plugin)

A PocketMine-MP plugin that prevents **third-party players** from interrupting 1v1 fights.  
When two players engage in combat, only those two can damage each other. Other players who try to interfere will have their attack canceled and see a custom message.  

This plugin also includes an optional feature to **hide all other players** from view during combat for a clean duel experience.

---

# Contact me
If there are any errors / features u want added contact me through discord: qzotx

## 🎥 Example Video

👉 [Watch how it works](https://youtu.be/BoivBnVT1t8)  

---


## ⚙️ Config (`config.yml`)

```yaml
# Message sent when a player tries to interrupt a fight
message: "⚔ You cannot interfere in an ongoing fight!"

# Combat time (in seconds)
# After the last hit, combat lasts this long before ending
combatTime: 10

# Hide other players while fighting?
# true = Only see your opponent
# false = See everyone normally
hidePlayers: true
