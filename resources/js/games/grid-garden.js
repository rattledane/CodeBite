import GameEngine from '../GameEngine';

export default class GridGarden extends GameEngine {
    constructor(config) {
        super(config);
    }
}

window.GridGarden = GridGarden;
