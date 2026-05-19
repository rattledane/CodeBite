const fs = require('fs');
const content = fs.readFileSync('./gridgarden/js/levels.js', 'utf8');
eval(content);

let php = '        $gridLevels = [\n';
levels.forEach((l, i) => {
    let instruction = l.instructions.id ? l.instructions.id : l.instructions.en;
    instruction = instruction.replace(/(<([^>]+)>)/gi, '').replace(/'/g, "\\'").replace(/\n/g, ' ').replace(/\r/g, '').trim();
    let style = Object.keys(l.style).map(k => k + ': ' + l.style[k]).join('; ');
    php += `            ['order' => ${i+1}, 'instruction' => '${instruction}', 'initial_code' => '', 'answer_key' => '${style}', 'hint' => 'Grid Garden Level ${i+1}', 'max_score' => ${i === 27 ? 200 : 100}],\n`;
});
php += '        ];\n';

fs.writeFileSync('./scratch_grid_seeder.php', php);
console.log('Done!');
