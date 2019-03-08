
export function versionNumber(version) {
    const [major, minor] = version.split('.');
    return Number(`${major}.${minor}`);
}