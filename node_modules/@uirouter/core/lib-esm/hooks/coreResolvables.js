/** @module hooks */ /** */
import { Transition } from '../transition/transition';
import { UIRouter } from '../router';
import { Resolvable } from '../resolve';
import { extend, inArray, mapObj } from '../common';
function addCoreResolvables(trans) {
    trans.addResolvable(Resolvable.fromData(UIRouter, trans.router), '');
    trans.addResolvable(Resolvable.fromData(Transition, trans), '');
    trans.addResolvable(Resolvable.fromData('$transition$', trans), '');
    trans.addResolvable(Resolvable.fromData('$stateParams', trans.params()), '');
    trans.entering().forEach(function (state) {
        trans.addResolvable(Resolvable.fromData('$state$', state), state);
    });
}
export var registerAddCoreResolvables = function (transitionService) {
    return transitionService.onCreate({}, addCoreResolvables);
};
var TRANSITION_TOKENS = ['$transition$', Transition];
var isTransition = inArray(TRANSITION_TOKENS);
// References to Transition in the treeChanges pathnodes makes all
// previous Transitions reachable in memory, causing a memory leak
// This function removes resolves for '$transition$' and `Transition` from the treeChanges.
// Do not use this on current transitions, only on old ones.
export var treeChangesCleanup = function (trans) {
    // If the resolvable is a Transition, return a new resolvable with null data
    var replaceTransitionWithNull = function (r) {
        return isTransition(r.token) ? Resolvable.fromData(r.token, null) : r;
    };
    var cleanPath = function (path) { return path.map(function (node) {
        var resolvables = node.resolvables.map(replaceTransitionWithNull);
        return extend(node.clone(), { resolvables: resolvables });
    }); };
    var treeChanges = trans.treeChanges();
    mapObj(treeChanges, cleanPath, treeChanges);
};
//# sourceMappingURL=coreResolvables.js.map